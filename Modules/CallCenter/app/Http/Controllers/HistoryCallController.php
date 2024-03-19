<?php

namespace Modules\CallCenter\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HistoryCallController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('callcenter::index');
    }
}
