<?php

namespace Modules\CallCenter\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\FileUploadTrait;
class CallInController extends Controller
{
    use FileUploadTrait;
    public function dataCallInBack(Request $request){
        $Path = 'logs';
        $this->makeFolder($Path);
        Storage::prepend('/logs/callin.txt',  $request);
        dd("ngaaa");
    }
}
