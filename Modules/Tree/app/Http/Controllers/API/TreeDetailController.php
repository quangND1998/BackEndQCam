<?php

namespace Modules\Tree\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Tree\app\Models\Tree;

class TreeDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function detail(Request $request, $id)
    {
        $tree = Tree::with('history_care.activityCare', 'images')->find($id);
        if ($tree) {
            $response = [
                'tree' => $tree,
                'history_care' => $tree->history_care()->select('*', DB::raw('DATE(date) as dategroup'))->orderBy('date', 'desc')->groupBy('dategroup')
            ];
            return response()->json($response, 200);
        }
        return response()->json('Not found', 404);
    }
}
