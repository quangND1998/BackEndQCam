<?php
namespace App\Service;

use App\Models\OtpVerify;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class OtpService {
    
    public function isOtpExpried($otp_number){
        $otp = OtpVerify::where('otp_number',$otp_number)->first();
        if($otp){
            if($otp->expried_at <= Carbon::now()){
                return false;
            }
            else{
                $otp->delete();
                return true;
            }
        }
        else{
            return false;
        }
    }

    public function createToken(){
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(config('fptsms.API_URL').'oauth2/token', [
            'client_id' => config('fptsms.client_id'),
            'client_secret' =>config('fptsms.client_secret'),
            "scope"=> config('fptsms.scope'),
            "session_id"=>  config('fptsms.session_id'),
            "grant_type"=>config('fptsms.grant_type')
        ]);  
        $data= $response->json();
        if($response->ok()){
            return $data['access_token'];
        }
        else{
            return null;
        }
        
    }

    public function sendSMS($token, $message, $phone){
   
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(config('fptsms.API_URL').'api/push-brandname-otp', [
            'access_token' => $token,
            "session_id"=>  config('fptsms.session_id'),
            "BrandName"=>config('fptsms.brand_name'),
            "Phone"=> $phone,
            'Message' => base64_encode($message),
            "RequestId"=>"tranID-Core01-987654321"
        ]);  
        return $response;
        
    }


    public function createOtp($second, $user){
        $otp = OtpVerify::create([
            'otp_number' => random_int(100000, 999999),
            'expried_at' => Carbon::now()->addSecond($second),
            'time_live' => $second,
            'user_id' => $user->id
        ]);
        return $otp;
    }


    public function updateOtp($otp){
        $new_otp = OtpVerify::where('otp_number',$otp)->first();
        if($new_otp){
           $new_otp->update([
                'expried_at' => Carbon::now()->addSecond(20),
           ]);
        }
        return $new_otp;
    }
    
}