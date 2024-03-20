<?php

namespace Modules\CallCenter\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\FileUploadTrait;
use Modules\CallCenter\app\Models\HistoryCall;
use Monolog\Logger;
class CallInController extends Controller
{
    use FileUploadTrait;
    public function dataCallInBack(Request $request){
        // $Path = 'logs';
        // $this->makeFolder($Path);
        // Storage::disk('public')->put('logs/callin.txt', "ngaa");
        // logger($request);
        // Storage::disk('public')->append('logs/callin.txt', $request);
        // return $request;
        // dd("ngaaa");
        // dd($request['application']);
        // $historyCall = HistoryCall::where('sip_call_id',$request['sip_call_id'])->first();
        // if($historyCall){
        //     $historyCall = HistoryCall::updated([
        //         "call_id" =>  $request['call_id'],
        //         "sip_call_id" => $request['sip_call_id'],
        //         "status" => $request['status'],
        //         "duration" => $request['duration'],
        //         "direction" => $request['direction'],
        //         "from_number" =>$request['from_number'],
        //         "to_number" => $request['to_number'],
        //         "time_started" => $request['time_started'],
        //         "time_answered" => $request['time_answered'],
        //         "time_ended" => $request['time_ended'],
        //         "billsec" => $request['billsec'],
        //         "called_count" => $request['called_count']
        //     ]);
        // }else{
        //     if($request['application'] == "dial"){
        //         $historyCall = HistoryCall::create([
        //             "call_id" =>  $request['call_id'],
        //             "sip_call_id" => $request['sip_call_id'],
        //             "status" => $request['status'],
        //             "duration" => $request['duration'],
        //             "direction" => $request['direction'],
        //             "from_number" =>$request['from_number'],
        //             "to_number" => $request['to_number'],
        //             "time_started" => $request['time_started'],
        //             "time_answered" => $request['time_answered'],
        //             "time_ended" => $request['time_ended'],
        //             "billsec" => $request['billsec'],
        //             "called_count" => $request['called_count']
        //         ]);

        //     }
        // }
        // $Path = 'callcenter';
        // $this->makeFolder($Path);
        // $destinationPath = 'callcenter'; // Đường dẫn đích để lưu trữ tệp
        // if ($request['recording_url']) {
        //     // Tải tệp từ URL
        //     $fileContents = file_get_contents($request['recording_url']);
        //     $fileName = 'voice_'.$request['call_id'];
        //     $historyCall->recording_url = $this->downloadFile($destinationPath,$fileName,$fileContents);
        // }
        // $historyCall->save();
        // return $historyCall;

    }
}
