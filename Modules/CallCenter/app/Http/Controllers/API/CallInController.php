<?php

namespace Modules\CallCenter\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\FileUploadTrait;
use Monolog\Logger;
class CallInController extends Controller
{
    use FileUploadTrait;
    public function dataCallInBack(Request $request){
        // $Path = 'logs';
        // $this->makeFolder($Path);
        // Storage::disk('public')->put('logs/callin.txt', "ngaa");
        logger($request);
        Storage::disk('public')->append('logs/callin.txt', $request);
        return $request;
        // dd("ngaaa");
    }
}
