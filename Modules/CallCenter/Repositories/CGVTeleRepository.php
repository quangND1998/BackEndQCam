<?php

namespace Modules\CallCenter\Repositories;
use App\Http\Controllers\Traits\ConnectHttpFunction;
use Modules\CallCenter\app\Models\HistoryCall;
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Http;
class CGVTeleRepository
{
    use ConnectHttpFunction;
    use FileUploadTrait;
    public $API_KEY_CALL = "b484af9e-4293-4943-b195-e32b626dcb2f";
    public function getToken(){
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
    public function getCdr(){
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
    public function getCallDetail($idCall){

        $token = session('access_token');
        if($token == null){
            $token = $this->getToken();
        }

        $url = "https://api.mobilesip.vn/v1/cdr/" .$idCall;

        $result =  Http::withToken($token)->get($url,[
            'api_key' => $this->API_KEY_CALL
        ]);

        if($result['id']){
            return $historyCall = $this->saveCallDetail($result);
        }
        return $result;
    }
    public function saveCallDetail($data){
        $historyCall = $this->saveData($data);
        $Path = 'callcenter';
        $this->makeFolder($Path);
        $destinationPath = '/public/callcenter/'; // Đường dẫn đích để lưu trữ tệp
        if ($data['recording_url']) {
            // Tải tệp từ URL
            $fileContents = file_get_contents($data['recording_url']);
            $fileName = 'voice_'.$data['id'];
            $historyCall->recording_url = $this->downloadFile($destinationPath,$fileName,$fileContents);
        }
        $historyCall->save();
        return $historyCall;
    }
    public function saveData($data){
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
}
