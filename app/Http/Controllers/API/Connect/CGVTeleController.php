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
use Modules\CustomerService\app\Models\DistributeCall;
use Modules\Order\app\Models\OrderPackage;
use Modules\CallCenter\Repositories\CGVTeleRepository;
class CGVTeleController extends Controller
{
    public $cgvTeleRepository;

    use ConnectHttpFunction;
    use FileUploadTrait;
    public $API_KEY_CALL = "b484af9e-4293-4943-b195-e32b626dcb2f";
    public function __construct(CGVTeleRepository $cgvTeleRepository)
    {

        $this->cgvTeleRepository = $cgvTeleRepository;
    }
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

        $orderPackages = OrderPackage::where('user_id', 3)
            ->where('status', 'complete')
            ->get();
        $distributeCalls = DistributeCall::whereIn('order_package_id', $orderPackages->pluck('id')->toArray())
            ->whereNotIn('state', ['done', 'remind_call_back'])
            ->where('date_call', date('Y-m-d'))
            ->get();
        if ($distributeCalls->count() > 0) {
            dispatch(new saveDataCall($idCall, $distributeCalls->pluck('id')->toArray()));
                // ->delay(now()->addMinutes(15));
        }
    }
}
