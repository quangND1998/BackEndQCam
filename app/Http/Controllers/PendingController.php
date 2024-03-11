<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\Remind;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
class PendingController extends Controller
{
    //
    public function index(Request $request){
        if($request->fromDate == null){
            $fromDate = Carbon::now()->startOfWeek();
            $todate =  Carbon::now()->endOfWeek();
        }else{
            $fromDate = Carbon::createFromFormat('d/m/Y',$request->fromDate)->format('Y-m-d H:i');
            $todate =  Carbon::createFromFormat('d/m/Y',$request->toDate)->format('Y-m-d H:i');
        }
        $offsetWeek = $this->getOffsetWeek($todate);
        $reminds = Remind::with('productServiceOwner.order_package.product_service','productServiceOwner.customer','csr')
            // ->where('remind_at', '>=', now()->toDateString())
            ->whereBetween('remind_at', [$fromDate, $todate])
            ->paginate(10);
        // return $reminds;
        return Inertia::render('Modules/CSKH/Pending/index',compact('reminds','offsetWeek'));
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
