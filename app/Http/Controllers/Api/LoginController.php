<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Base2Controller;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInfor;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Service\OtpService;
use App\Jobs\OtpEndTimeJob;
use Carbon\Carbon;
use App\Http\Controllers\Traits\FileUploadTrait;

class LoginController extends Base2Controller
{
    use FileUploadTrait;
    public function login(Request $request)
    {

        if (
            Auth::attempt(['phone_number' => request('code'), 'password' => request('password')]) ||
            Auth::attempt(['cic_number' => request('code'), 'password' => request('password')])
        ) {

            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['user'] =  $user->load('infor');
            $success['user']['can'] = $user->getRolesArray();
            if ($user->hasAnyRole(['shipper', 'super-admin', 'customer'])) {
                return $this->sendResponse($success, 'User login successfully.');
            }
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }


    protected function loginOtp(OtpService $otpService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        $user = User::where('phone_number', preg_replace('/\s+/', '', $request->phone_number))->first();
        if (!$user) {
            return response()->json("Số điện thoại chưa được đăng ký với hệ thống", 404);
        }
        if (!$user->hasAnyRole(['shipper', 'super-admin', 'customer'])) {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }

        $access_token = $otpService->createToken();

        if ($access_token) {
            $user->otps()->delete();
            $otp =  $otpService->createOtp(5, $user);
            OtpEndTimeJob::dispatch($otp)->delay(Carbon::now()->addMinute(5));
            $message =  "Ma xac nhan dang nhap ung dung Cam Mat Troi la " . $otp->otp_number . ".Tuyet doi KHONG cung cap ma OTP cho bat ky ai, ke ca nhan vien cua CAMMATTROI";
            $response =  $otpService->sendSMS($access_token, $message, preg_replace('/\s+/', '', $request->phone_number));
          
            if ($response->ok()) {
                $data = $response->json();
                if(array_key_exists('error', $data) && $data['error']== 1014) {
                    return response()->json("Có lỗi xảy ra", 400);
                }
              
                return response()->json('We send otp to your phone ' . $request->phone_number, 200);

            } else {
             
                $response = $response->json();
              
                if ($response['error'] == 1014) {
                    return response()->json("Số điện thoại không hợp lệ", 400);
                }
                if ($response['error']) {
                    return response()->json("Lỗi xảy ra", 400);
                }
            }
        }
        return response()->json("Lỗi xảy ra", 404);
    }


    protected function verify(OtpService $otpService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }


        if ($otpService->isOtpExpried($request->verification_code)) {
            $user = User::where('phone_number', preg_replace('/\s+/', '', $request->phone_number))->first();
            Auth::login($user);
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['user'] =  $user->load('infor');
            $success['user']['can'] = $user->getRolesArray();
            return $this->sendResponse($success, 'User login successfully.');
        }
        return response()->json('Mã code không chính xác hoặc đã sử dụng!. Vui lòng thử lại', 404);
    }

    public function logout(Request $request)
    {

        $token = PersonalAccessToken::findToken(request()->bearerToken());
        if ($token) {
            $user = Auth::user();
            $token->delete();
            // $user->tokens()->delete();
            // $user = $token->tokenable;
            // $user->tokens()->delete();
            return response()->json('You have successfully logged out.', Response::HTTP_OK);
        }
        return response()->json('Failed to logout, please try again.', Response::HTTP_BAD_REQUEST);
    }


    public function updatePassword(OtpService $otpService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        #Match The Old Password
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return $this->sendError('Mật khẩu cũ không chính xác.', null, 400);
        }




        $user = Auth::user();
        if (!$user) {
            return response()->json("Số điện thoại chưa được đăng ký với hệ thống", 400);
        }
        if (!$user->hasAnyRole(['shipper', 'super-admin', 'customer'])) {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }

        $access_token = $otpService->createToken();

        if ($access_token) {
            $user->otps()->delete();

            $otp =  $otpService->createOtp(5, $user);
            OtpEndTimeJob::dispatch($otp)->delay(Carbon::now()->addMinute(5));
            $message = "CamMatTroi: Vui long nhap ma OTP " . $otp->otp_number . " de xac thuc. Ma nay se het han sau 5 phut. Tuyet doi KHONG cung cap ma OTP cho bat ky ai, ke ca nhan vien cua CamMatTroi.";
            $response =  $otpService->sendSMS($access_token, $message, preg_replace('/\s+/', '', $user->phone_number));

            if ($response->ok()) {

                return response()->json('We send otp to your phone ' . $user->phone_number, 200);
            } else {
                $response = $response->json();
                return $response;
                if ($response['error'] == 1014) {
                    return response()->json("Số điện thoại không hợp lệ", 400);
                }
                if ($response['error']) {
                    return response()->json("Lỗi xảy ra", 400);
                }
            }
        } else {

            return response()->json("Số điện thoại chưa được đăng ký với hệ thống", 400);
        }
    }

    public function sendOtp(OtpService $otpService)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json("Số điện thoại chưa được đăng ký với hệ thống", 404);
        }
        if (!$user->hasAnyRole(['shipper', 'super-admin', 'customer'])) {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }

        $access_token = $otpService->createToken();

        if ($access_token) {
            $user->otps()->delete();

            $otp =  $otpService->createOtp(5, $user);
            OtpEndTimeJob::dispatch($otp)->delay(Carbon::now()->addMinute(5));
            $message = "CamMatTroi: Vui long nhap ma OTP " . $otp->otp_number . " de xac thuc. Ma nay se het han sau 5 phut. Tuyet doi KHONG cung cap ma OTP cho bat ky ai, ke ca nhan vien cua CamMatTroi.";
            $response =  $otpService->sendSMS($access_token, $message, preg_replace('/\s+/', '', $user->phone_number));

            if ($response->ok()) {

                return response()->json('Mã OTP đã được gửi đến số điện thoại ' . $user->phone_number, 200);
            } else {
                $response = $response->json();
                if ($response['error'] == 1014) {
                    return response()->json("Số điện thoại không hợp lệ", 400);
                }
                if ($response['error']) {
                    return response()->json("Lỗi xảy ra", 400);
                }
            }
        } else {

            return response()->json("Số điện thoại chưa được đăng ký với hệ thống", 400);
        }
    }


    protected function verifyChangePassword(OtpService $otpService, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => ['required', 'numeric'],
            'new_password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }


        if ($otpService->isOtpExpried($request->verification_code)) {

            User::whereId(Auth::user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json('Cập nhật mật khẩu thành công.', Response::HTTP_OK);
        }
        return response()->json('Mã code không chính xác hoặc đã sử dụng!. Vui lòng thử lại', 400);
    }

    public function getFireBaseToken(Request $request)
    {
        $user = Auth::user();

        $user->fcm_token = $request->token;
        $user->save();
        $success['user'] =  $user;
        return $this->sendResponse($success, 'Get Token successfully.');
    }


    public function updateUserInfor(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cic_number' => 'required|unique:users,cic_number,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone_number,' . $user->id,
            'sex' => 'required',
            'address' => 'required',
            'city' => 'required',
            'wards' => 'required',
            'district' => 'required',
            // 'date_of_birth' => 'nullable|date',

            // 'cic_date' => 'nullable|date',
            // 'cic_date_expried' => 'nullable|date|after:cic_date',
            'image' => 'nullable',
            'image' => 'mimes:jpeg,png,jpg|max:4096',
        ], [
            'name.required' => 'Vui lòng nhập Họ và Tên',
            // 'cic_number.required' => 'Vui lòng nhập số căn cước công dân',
            // 'cic_number.unique' => 'Số căn cước này đã đăng ký với 1 tài khoản khác',
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.unique' => 'Địa chỉ Email này đã đăng ký với 1 tài khoản khác',
            'city.required' => 'Địa chỉ nơi ở không bỏ trống , chúng tôi sẽ căn cứ vào địa chỉ này để giao hàng',
            'wards.required' => 'Địa chỉ nơi ở không bỏ trống , chúng tôi sẽ căn cứ vào địa chỉ này để giao hàng',
            'district.required' => 'Địa chỉ nơi ở không bỏ trống , chúng tôi sẽ căn cứ vào địa chỉ này để giao hàng',
            'address.required' => 'Địa chỉ nơi ở không bỏ trống , chúng tôi sẽ căn cứ vào địa chỉ này để giao hàng',
            // 'cic_date_expried.after' => 'Phải là một ngày sau Ngày Cấp trên căn cước',
            'image.mimes' => 'Ảnh đại diện phải là định dạng:jpeg,png,jpg',
            'image.max' => 'Ảnh đại diện không được lớn hơn 4MB'
        ]);
        $savePath = 'profile';
        $this->makeFolder($savePath);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        if (!$user->infor) {
            $userInfor = UserInfor::create($request->all());
            $userInfor->phone_number = preg_replace('/\s+/', '', $request->phone_number);
            $userInfor->user_id = $user->id;
            if ($request->image) {
                $userInfor->photo_url = $this->uploadImage($request->file('image'), $savePath);
            }
            $userInfor->save();
        } else {
            $user->infor->update($request->all());
            $user->infor->status = false;
            $user->infor->phone_number = preg_replace('/\s+/', '', $request->phone_number);
            if ($request->image) {
                $user->infor->photo_url = $request->file('image') ? $this->updateImage($request->file('image'), $savePath, $user->infor->photo_url) : $user->infor->photo_url;
            }
            $user->infor->save();
        }


        // $response = [
        //     'message' => 'Chúng tôi đã nhận yêu cầu thay đổi thông tin tài khoản của bạn',
        //     'user' =>  $user->load('infor'),
        // ];
        $success['user'] =  $user->load('infor');
        $success['user']['can'] = $user->getRolesArray();
        return $this->sendResponse($success, 'Chúng tôi đã nhận yêu cầu thay đổi thông tin tài khoản của bạn');
    }

    public function getUser()
    {
        $user = Auth::user();
        $success['user'] =  $user->load('infor');
        $success['user']['can'] = $user->getRolesArray();
        return $this->sendResponse($success, 'User login successfully.');
    }
}
