<?php

namespace Modules\Order\Repositories;

use App\Models\User;
use Modules\Order\app\Models\OrderPackage;
use App\Models\Commission;
use App\Models\CommissionType;
use Illuminate\Support\Carbon;
class CSKHRepository
{
    public function getOffsetWeek($date){
        // Tạo đối tượng Carbon cho tuần hiện tại
        $currentWeek = Carbon::now()->startOfWeek();

        // Tạo đối tượng Carbon cho tuần khác
        $otherWeek = Carbon::parse($date)->startOfWeek();

        // Tính khoảng cách giữa hai tuần
        $weekDiff = $currentWeek->diffInWeeks($otherWeek);
        return $weekDiff;
    }
    public function getOrderPackage($request){
        if($request->fromDate == null){
            $fromDate = Carbon::now()->startOfWeek();
            $todate =  Carbon::now()->endOfWeek();
        }else{
            $fromDate = Carbon::createFromFormat('d/m/Y',$request->fromDate)->format('Y-m-d H:i');
            $todate =  Carbon::createFromFormat('d/m/Y',$request->toDate)->format('Y-m-d H:i');
        }
        $results = OrderPackage::with(['customer','product_service','product_service_owner.product','distributeCall' => function($q) use ($fromDate,$todate) {
            $q->whereBetween('date_call', [$fromDate, $todate]);
        }, 'distributeCall.cskh'])->role()
        ->whereHas(
            'distributeCall',
            function ($q) use ($fromDate,$todate) {
                $q->whereBetween('date_call', [$fromDate, $todate]);
            }
        )
        ->where('status','complete')
        ->orderBy('user_id')->orderBy('created_at', 'desc');
         return $results;
    }
    public function getOrderNotCSKH($request){
        if($request->fromDate == null){
            $fromDate = Carbon::now()->startOfWeek();
            $todate =  Carbon::now()->endOfWeek();
        }else{
            $fromDate = Carbon::createFromFormat('d/m/Y',$request->fromDate)->format('Y-m-d H:i');
            $todate =  Carbon::createFromFormat('d/m/Y',$request->toDate)->format('Y-m-d H:i');
        }
        $results = OrderPackage::with(['customer','product_service','product_service_owner.product','distributeCall' => function($q) use ($fromDate,$todate) {
            $q->whereBetween('date_call', [$fromDate, $todate]);
        }, 'distributeCall.cskh'])->role()
        ->whereHas(
            'distributeCall',
            function ($q) use ($fromDate,$todate) {
                $q->whereBetween('date_call', [$fromDate, $todate])->where('cskh_id',null);
            }
        )
        ->where('status','complete')
        ->orderBy('user_id')->orderBy('created_at', 'desc');
         return $results;
    }
}
