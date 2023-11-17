<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Base2Controller;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class LoginController extends Base2Controller
{
    public function login(Request $request)
    {

        if (
            Auth::attempt(['phone_number' => request('code'), 'password' => request('password')]) ||
            Auth::attempt(['cic_number' => request('code'), 'password' => request('password')])
        ) {

            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['user'] =  $user;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }


    protected function loginOtp(Request $request)
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
        $response = Http::withHeaders([
            'Cookie' => 'ASP.NET_SessionId=nhizlj210llu1cbvucp133aa',
            'Content-Type' => 'application/json'
        ])->get('https://rest.esms.vn/MainService.svc/json/SendMessageAutoGenCode_V4_get', [
            "Phone" => $request->phone_number,
            "ApiKey" => config('esms.esms_api_key'),
            "SecretKey" => config('esms.esms_secret_key'),
            "SmsType" => 2,
            "TimeAlive" => 45,
            "NumCharOfCode" => 6,
            "Brandname" => "Baotrixemay",
            "Type" => 2,
            "message" => "{OTP} la ma xac minh dang ky Baotrixemay cua ban",
            "IsNumber" => 1
        ]);
        $data = $response->json();
        if ($data["CodeResult"] == 100) {
            return response()->json('We send otp to your phone ' . $request->phone_number, 200);
        }
        return response()->json("Lỗi xảy ra", 404);
    }


    protected function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string'],
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $response = Http::withHeaders([
            'Cookie' => 'ASP.NET_SessionId=nhizlj210llu1cbvucp133aa',
            'Content-Type' => 'application/json'
        ])->get('https://rest.esms.vn/MainService.svc/json/CheckCodeGen_V4_get', [
            "Phone" => $request->phone_number,
            "ApiKey" => config('esms.esms_api_key'),
            "SecretKey" => config('esms.esms_secret_key'),
            "Code" => $request->verification_code,

        ]);
        $data = $response->json();

        if ($data['CodeResult'] == 100) {
            $user = User::where('phone_number', preg_replace('/\s+/', '', $request->phone_number))->first();
            Auth::login($user);
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['user'] =  $user;
            return $this->sendResponse($success, 'User login successfully.');
        }

        return response()->json('Mã code không chính xác hoặc đã sử dụng!. Vui lòng thử lại', 404);
    }

    public function logout(Request $request)
    {

        $token = PersonalAccessToken::findToken(request()->bearerToken());
        if ($token) {
            $token->delete();
            // $user = $token->tokenable;
            // $user->tokens()->delete();
            return response()->json('You have successfully logged out.', Response::HTTP_OK);
        }
        return response()->json('Failed to logout, please try again.', Response::HTTP_BAD_REQUEST);
    }


    public function updatePassword(Request $request)
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
            return $this->sendError('Mật khẩu cũ không chính xác.', 404);
        }


        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json('Cập nhật mật khẩu thành công.', Response::HTTP_OK);
    }
}
