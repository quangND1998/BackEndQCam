<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\CallCenter\app\Models\HistoryCall;
use App\Http\Controllers\Traits\FileUploadTrait;
class saveDataCallBack implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use FileUploadTrait;
    /**
     * Create a new job instance.
     */
    public $dataCall;
    public function __construct($dataCall)
    {
        $this->dataCall = $dataCall;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         $this->saveData();
    }
    public function saveData(){
        if(isset($this->dataCall['application'])){
            if($this->dataCall['application'] == "dial"){
                $historyCall = HistoryCall::where('sip_call_id',$this->dataCall['sip_call_id'])->first();

                if($historyCall){
                    $historyCall->updated([
                        "call_id" =>  $this->dataCall['call_id'],
                        "sip_call_id" => $this->dataCall['sip_call_id'],
                        "status" => $this->dataCall['status'],
                        "duration" => $this->dataCall['duration'],
                        "direction" => $this->dataCall['direction'],
                        "from_number" =>$this->dataCall['from_number'],
                        "to_number" => $this->dataCall['to_number'],
                        "time_started" => $this->dataCall['time_started'],
                        "time_answered" => $this->dataCall['time_answered'],
                        "time_ended" => $this->dataCall['time_ended'],
                        "billsec" => $this->dataCall['billsec'],
                    ]);
                }else{


                        $historyCall = HistoryCall::create([
                            "call_id" =>  $this->dataCall['call_id'],
                            "sip_call_id" => $this->dataCall['sip_call_id'],
                            "status" => $this->dataCall['status'],
                            "duration" => $this->dataCall['duration'],
                            "direction" => $this->dataCall['direction'],
                            "from_number" =>$this->dataCall['from_number'],
                            "to_number" => $this->dataCall['to_number'],
                            "time_started" => $this->dataCall['time_started'],
                            "time_answered" => $this->dataCall['time_answered'],
                            "time_ended" => $this->dataCall['time_ended'],
                            "billsec" => $this->dataCall['billsec'],
                        ]);

                }
            }
        }
        // $Path = 'callcenter';
        // $this->makeFolder($Path);
        // $destinationPath = '/callcenter/'; // Đường dẫn đích để lưu trữ tệp
        // if ($this->dataCall['recording_url']) {
        //     // Tải tệp từ URL
        //     $fileContents = file_get_contents($this->dataCall['recording_url']);
        //     $fileName = 'voice_'.$this->dataCall['call_id'];
        //     $historyCall->recording_url = $this->downloadFile($destinationPath,$fileName,$fileContents);
        // }
        // $historyCall->save();
    }
}
