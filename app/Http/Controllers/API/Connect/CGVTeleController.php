<?php

namespace App\Http\Controllers\API\Connect;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\ConnectHttpFunction;
use Illuminate\Support\Facades\Http;
class CGVTeleController extends Controller
{
    use ConnectHttpFunction;
    public function getToken(){
        $url = "https://api.mobilesip.vn/v1/auth/token";
        $params = [
            'api_key' => "b484af9e-4293-4943-b195-e32b626dcb2f"
        ];
        // $result = $this->httpPost($url,$params);
        $result = Http::post($url, [
            'api_key' => "b484af9e-4293-4943-b195-e32b626dcb2f"
        ]);

        $token =  $result['data']['token'];
        if($token != null){
            $data_cdr = $this->getCdr($token);
            return ($data_cdr);
        }
        return $token;
        
    }
    public function getCdr($token){
        // dd($data_cdr);
        $url = "https://api.mobilesip.vn/v1/cdr";
        $params = [
            'api_key' => "b484af9e-4293-4943-b195-e32b626dcb2f"
        ];
        Http::get('http://example.com/users', [
            'name' => 'Taylor',
            'page' => 1,
        ]);
        $result =  Http::withToken($token)->get($url,[
            'api_key' => "b484af9e-4293-4943-b195-e32b626dcb2f"
        ]);
        return $result;
    }
}
