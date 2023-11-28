<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreviewController extends Controller
{
    //
    public function previewData(Request $request){
        $url = $request->input('url');
        if (file_exists($url)) {
        return view('iframe',compact('url'));
        }else{
            return response()->json('link not found');
        }
    }
}
