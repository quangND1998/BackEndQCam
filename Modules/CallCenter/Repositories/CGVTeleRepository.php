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

        $result =  Http::withToken($token)->get($url,[
            'api_key' => $this->API_KEY_CALL
        ]);

        if($result['id']){
            $callHistory = $this->saveCallDetail($result);
            $status = $this->getExpectedStatus($result['status'], $result['duration']);
            $this->updateDistributeCall($callHistory->id, $distributeCallIds, $status);

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

    public function updateDistributeCall($callHistoryId, $distributeCallIds, $status)
    {
        $distributeCalls = DistributeCall::whereIn('id', $distributeCallIds)
            ->with('orderPackage.product_service_owner')
            ->get();
        $distributeCalls->each(function ($distributeCall) use ($callHistoryId, $status) {
            $distributeCall->history_call_id = $callHistoryId;
            if (is_array($status)) { // Call success check if order or remind was created
                $productServiceOwnerId = $distributeCall->orderPackage->product_service_owner->id;
                $order = Order::where('created_at', $distributeCall->date_call)
                    ->where('product_service_owner_id', $productServiceOwnerId)
                    ->first();
                if ($order) { // Call success and order created
                    $distributeCall->state = 'done';
                } else {
                    $remind = Remind::where('created_at', $distributeCall->date_call)
                    ->where('product_service_owner_id', $productServiceOwnerId)
                    ->first();
                    if ($remind) { // Call success and remind created
                        $distributeCall->state = 'remind_call_back';
                    }
                }
            } else {
                // Update status normally
                $distributeCall->state = $status;
            }
            $distributeCall->save();
        });
    }

    public function getExpectedStatus($status, $duration)
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
