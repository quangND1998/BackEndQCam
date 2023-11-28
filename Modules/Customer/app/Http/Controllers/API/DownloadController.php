<?php

namespace Modules\Customer\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DownloadController extends Controller
{
    public function downloadModel(Request $request){
        $url = $request->input('url');
        
        if (file_exists($url)) {
            return response()->download($url);
        } else {
            return response()->json('link not found');
        }
    }
}
