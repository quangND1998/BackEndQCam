<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\DistributeCalls;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\DistributeCall;
use Modules\Order\app\Models\Order;

class GetDistributeCall extends Controller
{
    public function __invoke(Request $request)
    {
        $fromDate = Carbon::createFromFormat('Y-m-d', $request->from_date)->subDay(1)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('Y-m-d', $request->to_date)->addDay(1)->format('Y-m-d');
        $distributeCalls = DistributeCall::where('cskh_id', auth()->id())
            ->with(['orderPackage.customer', 'orderPackage.product_service', 'orderPackage.product_service_owner'])
            ->whereBetween('date_call', [$fromDate, $toDate])
            ->get();

        $orderPackagePlans = [];
        $distributeCalls->each(function ($distributeCall) use (&$orderPackagePlans) {
            if (isset($orderPackagePlans[$distributeCall->order_package_id])) {
                $dayOfWeek = Carbon::parse($distributeCall->date_call)->dayOfWeek ? Carbon::parse($distributeCall->date_call)->dayOfWeek : 7;
                $orderPackagePlans[$distributeCall->order_package_id]['plans']['day_' . $dayOfWeek]['call'] = $distributeCall->state;
            } else {
                $dayOfWeek = Carbon::parse($distributeCall->date_call)->dayOfWeek? Carbon::parse($distributeCall->date_call)->dayOfWeek : 7;
                $orderPackagePlans[$distributeCall->order_package_id] = [
                    'orderPackageId' => $distributeCall->orderPackage->idPackage,
                    'lifeTime' => $distributeCall->orderPackage->product_service->life_time,
                    'customerName' => $distributeCall->orderPackage->customer->name,
                    'activeDate' => $distributeCall->orderPackage->time_approve,
                    'customerId' => $distributeCall->orderPackage->customer->id,
                    'plans' => [
                        'day_' . $dayOfWeek => [
                            'call' => $distributeCall->state,
                            'order' => false
                        ]
                    ],
                ];
            }
        });

        Order::where('sale_id', auth()->id())
            ->with(['product_service.order_package', 'customer', 'product_service.product'])
            ->where('type', 'gift_delivery')
            ->whereIn('status', ['pending', 'packing', 'shipping', 'completed'])
            ->whereBetween('delivery_appointment', [$fromDate, $toDate])
            ->get()
            ->each(function ($order) use (&$orderPackagePlans) {
                $dayOfWeek = Carbon::parse($order->delivery_appointment)->dayOfWeek ? Carbon::parse($order->delivery_appointment)->dayOfWeek : 7;
                if (isset($orderPackagePlans[$order->product_service->order_id])) {
                    $orderPackagePlans[$order->product_service->order_id]['plans']['day_' . $dayOfWeek]['order'] = true;
                } else {
                    $orderPackagePlans[$order->product_service->order_id] = [
                        'orderPackageId' => $order->product_service->order_package->idPackage,
                        'lifeTime' => $order->product_service->product->life_time,
                        'customerName' => $order->customer->name,
                        'activeDate' => $order->product_service->order_package->time_approve,
                        'customerId' => $order->customer->id,
                        'plans' => [
                            'day_' . $dayOfWeek => [
                                'call' => null,
                                'order' => true
                            ]
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
