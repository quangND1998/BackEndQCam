<?php

namespace Modules\Tree\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Tree\app\Models\Tree;
class TreeDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function detail(Request $request,$id)
    {
        $tree = Tree::with('history_care.activityCare','images')->findOrFail($id);
        
    }
}
