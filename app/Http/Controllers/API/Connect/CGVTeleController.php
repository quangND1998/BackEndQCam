<?php

namespace App\Http\Controllers\API\Connect;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\ConnectHttpFunction;
use Illuminate\Support\Facades\Http;
use Modules\CallCenter\app\Models\HistoryCall;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Jobs\saveDataCall;
use Carbon\Carbon;

class CGVTeleController extends Controller
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
        saveDataCall::dispatch($idCall);
        return $idCall;
    }
}
