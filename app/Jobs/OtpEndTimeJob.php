<?php

namespace App\Jobs;

use App\Models\OtpVerify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OtpEndTimeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $otp;
    /**
     * Create a new job instance.
     */
    public function __construct( $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      
        if($this->otp){
            $otp = OtpVerify::find($this->otp->id);
         
            if($otp){
                $otp->delete();
            }
        }
       
    }
}
