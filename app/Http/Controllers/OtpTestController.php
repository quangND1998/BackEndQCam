<?php

namespace App\Http\Controllers;

use App\Jobs\OtpEndTimeJob;
use App\Service\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
class OtpTestController extends Controller
{
    public $otpService;
    public function __construct(OtpService $otpService)
    {

        $this->otpService = $otpService;
   
      
    }
    public function test(){
        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        // ])->post('https://app.sms.fpt.net/oauth2/token', [
        //     'client_id' => '0226A807f137ee2d33ed1c2f6aEdc910759a45A8',
        //     'client_secret' => 'f841e7B73a57171f8b1bb7798757017F4ce0515aE985bcfbe20e7eA6533897Fcaa6a5a92',
        //     "scope"=> "send_brandname_otp send_brandname",
        //     "session_id"=>  Session::getId(),
        //     "grant_type"=> "client_credentials"
        // ]);  
        $otp=  $this->otpService->createOtp(60, Auth::user());    
     
        OtpEndTimeJob::dispatch($otp)->delay(Carbon::now()->addSecond(60));
        return $otp;

        
    }

    public function checkOtp($otp){
        if($this->otpService->isOtpExpried($otp)){
            return 'otp đúng';
        }  
        else{
            return 'otp đã hết hạn, hoặc đã được dùng';
        }
    }
}
