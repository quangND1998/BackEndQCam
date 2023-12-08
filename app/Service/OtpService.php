<?php
namespace App\Service;

use App\Models\OtpVerify;
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