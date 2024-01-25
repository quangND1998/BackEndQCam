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
        $orderPackages = $this->getOrderPackage($request);
        $cskh = User::whereHas(
            'roles',
            function ($query) {
                $query->where('name', 'cskh');
            }
        )->get();
        // return $orderPackages;
        return Inertia::render('Modules/CSKH/Schedule', compact('orderPackages','cskh'));
    }
    public function getOrderPackage($request){
        if($request->fromDate == null){
            $fromDate = Carbon::now()->startOfWeek();
            $todate =  Carbon::now()->endOfWeek();
        }else{
            $fromDate = Carbon::createFromFormat('d/m/Y',$request->fromDate)->format('Y-m-d H:i');
            $todate =  Carbon::createFromFormat('d/m/Y',$request->toDate)->format('Y-m-d H:i');
        }
        $results = OrderPackage::with(['customer','product_service','product_service_owner.product','distributeDate','distributeCall' => function($q) use ($fromDate,$todate) {
            $q->whereBetween('date_call', [$fromDate, $todate]);
        }])->role()
        ->whereHas(
            'distributeCall',
            function ($q) use ($fromDate,$todate) {
                $q->whereBetween('date_call', [$fromDate, $todate]);
            }
        )
        ->where('status','complete')
        ->orderBy('user_id')->orderBy('created_at', 'desc');

        // [Carbon::parse($request->fromDate), Carbon::parse($request->toDate)]

        $orderPackages = $results->paginate($request->per_page ? $request->per_page : 10);
        return $orderPackages;
        // return $results;
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

        if($request->fromDate == null){
            $fromDate = Carbon::now()->startOfWeek();
            $todate =  Carbon::now()->endOfWeek();
        }else{
            $fromDate = Carbon::createFromFormat('d/m/Y',$request->fromDate)->format('Y-m-d H:i');
            $todate =  Carbon::createFromFormat('d/m/Y',$request->toDate)->format('Y-m-d H:i');
        }
        $orderPackages = $this->getOrderPackage($request);
        foreach($orderPackages as $order){
            $dateDistribute = $order->distributeDate()->whereBetween('date_recevie', [$fromDate, $todate])->get();
            // dd($dateDistribute);
            foreach($dateDistribute as $date){
                    $datecall = Carbon::parse($date->date_recevie)->subDays(2);
                    if($datecall->isSunday()){
                        $datecall = $datecall->addDays(1);
                    }
                    $distributeCall = new DistributeCall;
                    $distributeCall->date_call = $datecall;
                    $distributeCall->order_package_id = $order->id;
                    $distributeCall->cskh_id = $cskh_select[array_rand($cskh_select)];
                    $distributeCall->save();
            }
        }
        return $orderPackages;
    }
}
