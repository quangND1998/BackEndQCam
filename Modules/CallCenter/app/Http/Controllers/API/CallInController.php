<?php

namespace Modules\CallCenter\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Jobs\saveDataCallBack;
use App\Jobs\saveDataCall;
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

        // $idCall = "7302a522-24eb-4340-96bf-36acc37932c0";
        // dispatch(new saveDataCall($idCall, null));
         saveDataCallBack::dispatch($request->all());
        // if($request['application'] == "dial"){
        //     // dd($request['sip_call_id']);
        //     $historyCall = HistoryCall::create([
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
        //     dd($historyCall);

        // }
    }
}
