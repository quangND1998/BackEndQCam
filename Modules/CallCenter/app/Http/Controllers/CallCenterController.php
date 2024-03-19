<?php

namespace Modules\CallCenter\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Modules\CallCenter\app\Models\HistoryCall;

class CallCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->fromDate == null){
            $fromDate = Carbon::now()->startOfWeek();
            $todate =  Carbon::now()->endOfWeek();
        }else{
            $fromDate = Carbon::createFromFormat('d/m/Y',$request->fromDate)->format('Y-m-d H:i');
            $todate =  Carbon::createFromFormat('d/m/Y',$request->toDate)->format('Y-m-d H:i');
        }
        $offsetWeek = $this->getOffsetWeek($todate);
        $history_calls = HistoryCall::with('distributeCall.cskh','distributeCall.orderPackage.customer','distributeCall.orderPackage.product_service')->whereBetween('created_at', [$fromDate, $todate])
        ->paginate(10);
        // return $history_calls;
        return Inertia::render('Modules/CSKH/CallCenter/index',compact('history_calls','offsetWeek'));
    }
    public function getOffsetWeek($date){
        // Tạo đối tượng Carbon cho tuần hiện tại
        $currentWeek = Carbon::now()->startOfWeek();

        // Tạo đối tượng Carbon cho tuần khác
        $otherWeek = Carbon::parse($date)->startOfWeek();

        // Tính khoảng cách giữa hai tuần
        $weekDiff = $currentWeek->diffInWeeks($otherWeek);
        return $weekDiff;
    }
}
