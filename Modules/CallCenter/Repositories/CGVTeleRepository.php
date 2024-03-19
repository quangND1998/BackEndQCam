<?php

namespace Modules\CallCenter\Repositories;
use App\Http\Controllers\Traits\ConnectHttpFunction;
use Modules\CallCenter\app\Models\HistoryCall;
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Http;
use Modules\CustomerService\app\Models\DistributeCall;
use Modules\CustomerService\app\Models\Remind;
use Modules\Order\app\Models\Order;

class CGVTeleRepository
{
    use ConnectHttpFunction, FileUploadTrait;

    public $API_KEY_CALL = "b484af9e-4293-4943-b195-e32b626dcb2f";

    public function getToken()
    {
        $url = "https://api.mobilesip.vn/v1/auth/token";
        $result = Http::post($url, [
            'api_key' => $this->API_KEY_CALL
        ]);
        if($result['data']){
            $token =  $result['data']['token'];
            session(['access_token' => $token]);
            return $token;
        }

        return $result;

    }
    public function getCdr()
    {
        $token = session('access_token');
        if($token == null){
            $token = $this->getToken();
        }

        $url = "https://api.mobilesip.vn/v1/cdr";
        $params = [
            'api_key' => $this->API_KEY_CALL
        ];
        $result =  Http::withToken($token)->get($url,[
            $params
        ]);

        return $result;
    }

    public function getCallDetail($sipCallId, $distributeCallIds)
    {
        // dd($sipCallId);
        $token = session('access_token');
        if($token == null){
            $token = $this->getToken();
        }
        $url = "https://api.mobilesip.vn/v1/cdr/" . $sipCallId;

        // dd($url);
        // $url = "https://api.mobilesip.vn/v1/cdr/" ."6lsju60lp03cvn1el8t0";
        $result =  Http::withToken($token)->get($url,[
            'api_key' => $this->API_KEY_CALL
        ]);

        if($result['id']){
            $status = $this->getExpectedStaus($result['status'], $result['duration']);
            $this->updateDistributeCall($distributeCallIds, $status);

            return $this->saveCallDetail($result);
        }

        return $result;
    }

    public function saveCallDetail($data)
    {
        $historyCall = $this->saveData($data);
        $Path = 'callcenter';
        $this->makeFolder($Path);
        $destinationPath = 'callcenter'; // Đường dẫn đích để lưu trữ tệp
        if ($data['recording_url']) {
            // Tải tệp từ URL
            $fileContents = file_get_contents($data['recording_url']);
            $fileName = 'voice_'.$data['id'];
            $historyCall->recording_url = $this->downloadFile($destinationPath,$fileName,$fileContents);
        }
        $historyCall->save();

        return $historyCall;
    }

    public function saveData($data)
    {
        $historyCall = HistoryCall::create([
            "call_id" =>  $data['id'],
            "status" => $data['status'],
            "cause" =>  $data['cause'],
            "duration" => $data['duration'],
            "direction" => $data['direction'],
            "extension" => $data['extension'],
            "from_number" =>$data['from_number'],
            "to_number" => $data['to_number'],
            "receive_dest" => $data['receive_dest'],
            "time_started" => $data['time_started'],
            "time_answered" => $data['time_answered'],
            "time_ended" => $data['time_ended'],
            "time_ringging" => $data['time_ringging'],
            "billsec" => $data['billsec'],
            "called_count" => $data['called_count']
        ]);

        return $historyCall;
    }

    public function updateDistributeCall($distributeCallIds, $status)
    {
        $distributeCalls = DistributeCall::whereIn('id', $distributeCallIds)
            ->with('orderPackage.product_service_owner')
            ->get();
        $distributeCalls->each(function ($distributeCall) use ($status) {
            if (is_array($status)) {
                $productServiceOwnerId = $distributeCall->orderPackag->product_service_owner->id;
                $order = Order::where('created_at', $distributeCall->date_call)
                    ->where('product_service_owner_id', $productServiceOwnerId)
                    ->first();
                if ($order) {
                    $distributeCall->state = 'done';
                }
                $remind = Remind::where('created_at', $distributeCall->date_call)
                    ->where('product_service_owner_id', $productServiceOwnerId)
                    ->first();
                if ($remind) {
                    $distributeCall->state = 'remind_call_back';
                }
            } else {
                $distributeCall->state = $status;
            }
            $distributeCall->save();
        });
    }

    public function getExpectedStaus($status, $duration)
    {
        if ($status == "ANSWERED" && $duration > 10) {
            return ["done", "remind_call_back"];
        }

        if ($status == 'NO-ANSWERED') {
            return 'dontAnswer';
        }

        if ($status === 'ANSWERED' && $duration <= 10) {
            return 'hangup';
        }

        return 'no_action';
    }
}
