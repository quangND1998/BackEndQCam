<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\DistributeCalls;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\DistributeCall;

class GetDistributeCall extends Controller
{
    public function __invoke(Request $request)
    {
        $fromDate = Carbon::createFromFormat('Y-m-d', $request->from_date)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('Y-m-d', $request->to_date)->format('Y-m-d');
        $distributeCalls = DistributeCall::where('cskh_id', auth()->id())
            ->with(['orderPackage.customer', 'orderPackage.product_service'])
            ->whereBetween('date_call', [$fromDate, $toDate])
            ->get();
        $orderPackagePlans = [];
        $distributeCalls->each(function ($distributeCall) use (&$orderPackagePlans) {
            if (isset($orderPackagePlans[$distributeCall->order_package_id])) {
                $dayOfWeek = Carbon::parse($distributeCall->date_call)->dayOfWeek ? Carbon::parse($distributeCall->date_call)->dayOfWeek : 7;
                $orderPackagePlans[$distributeCall->order_package_id]['plans'][$dayOfWeek] = $distributeCall->state;
            } else {
                $dayOfWeek = Carbon::parse($distributeCall->date_call)->dayOfWeek? Carbon::parse($distributeCall->date_call)->dayOfWeek : 7;
                $orderPackagePlans[$distributeCall->order_package_id] = [
                    'orderPackageId' => $distributeCall->orderPackage->idPackage,
                    'lifeTime' => $distributeCall->orderPackage->product_service->life_time,
                    'customerName' => $distributeCall->orderPackage->customer->name,
                    'activeDate' => $distributeCall->orderPackage->time_approve,
                    'customerId' => $distributeCall->orderPackage->customer->id,
                    'plans' => [
                        $dayOfWeek => $distributeCall->state,
                    ],
                ];
            }
        });

        return response()->json([
            'message' => 'Ok',
            'orderPackagePlans' => collect($orderPackagePlans)->values()->all(),
        ]);
    }
}
