<?php

namespace Modules\Order\app\Http\Controllers\CSKH;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Inertia\Inertia;
use Modules\Order\app\Models\OrderPackage;
use Illuminate\Support\Carbon;
use Modules\CustomerService\app\Models\DistributeCall;

class CallDistributeController extends Controller
{
    public function getSchedule(Request $request){
        $orderPackages = $this->getOrderPackage($request)->paginate(10);
        if($request->fromDate == null){
            $fromDate = Carbon::now()->startOfWeek();
            $todate =  Carbon::now()->endOfWeek();
        }else{
            $fromDate = Carbon::createFromFormat('d/m/Y',$request->fromDate)->format('Y-m-d H:i');
            $todate =  Carbon::createFromFormat('d/m/Y',$request->toDate)->format('Y-m-d H:i');
        }
        $packageNotDistribute = OrderPackage::whereHas('distributeCall', function($q) use ($fromDate,$todate){
            $q->where('cskh_id',null)->whereBetween('date_call', [$fromDate, $todate]);
        })->count();
        $cskh = User::whereHas(
            'roles',
            function ($query) {
                $query->where('name', 'cskh');
            }
        )->get();
        return Inertia::render('Modules/CSKH/Schedule', compact('orderPackages','cskh','packageNotDistribute'));
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
    public function deviceSchedule(Request $request){
        $this->validate(
            $request,
            [
                'cskh_selected' => 'required',
            ]
        );
        $cskh_select = User::whereHas(
            'roles',
            function ($query) {
                $query->where('name', 'cskh');
            }
        )->where('isActive',1)
        ->whereIn('id',$request->cskh_selected)->pluck('id')->toArray();

        $orderPackages = $this->getOrderPackage($request)->get();
        $index =0;
        foreach($orderPackages as $order){
            foreach($order->distributeCall as $distributeCall){
                // $distributeCall->cskh_id = $cskh_select[array_rand($cskh_select)];
                $distributeCall->cskh_id = $cskh_select[$index];
                $distributeCall->save();
                $index++;
                if ($index >= count($cskh_select)) {
                    $index = 0;
                }
            }

        }
        return back()->with('success', "Đã chia công việc của tuần");
    }
}
