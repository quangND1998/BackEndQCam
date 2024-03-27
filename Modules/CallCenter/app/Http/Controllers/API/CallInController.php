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

        if(isset($request['application'])){
            if($request['application'] == "dial"){
                // saveDataCallBack::dispatch($request->all())->delay(30);
                return response()->json([
                    'message' => 'OK',
                ]);
            }
            return response()->json([
                'message' => 'not dial',
            ]);
        }
    }
}
