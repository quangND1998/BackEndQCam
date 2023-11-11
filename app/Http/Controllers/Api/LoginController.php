<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
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
        if (!User::where('phone_number', $request->phone_number)->exists()) {
            return $this->sendError('Không tìm thấy số điện thoại.', $validator->errors(), 404);
        } /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($request->phone_number, "sms");
        return response()->json('We send otp to your phone ' . $request->phone_number, 200);
    }


    protected function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string'],
        ]);



        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

        $twilio = new Client($twilio_sid, $token);

        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create(
                [
                    "to" => $request->phone_number,
                    "code" => $request->verification_code
                ]
            );

        if ($verification->valid) {

            $user = User::where('phone_number', $request->phone_number)->first();
            Auth::login($user);
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['user'] =  $user;

            return $this->sendResponse($success, 'User login successfully.');
        }
        return $this->sendError('Unauthorised.', ['error' => 'Invalid verification code entered!']);
    }
}
