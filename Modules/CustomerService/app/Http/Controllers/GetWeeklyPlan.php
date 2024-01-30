<?php

namespace Modules\CustomerService\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\CustomerService\app\Models\DistributeCall;
use Modules\CustomerService\app\Models\Remind;
use Modules\Order\app\Models\Order;

class GetWeeklyPlan extends Controller
{
    public function __invoke(Request $request)
    {
        $fromDate = Carbon::now()->startOfWeek()->subDay(1);
        $toDate = Carbon::now()->endOfWeek()->addDay(1);
        $remindData = Remind::where('user_id', auth()->id())
            ->with('productServiceOwner', function ($query) {
                $query->with(['order_package.product_service', 'customer']);
            })
            ->where('remind_at', '>=', now()->toDateString())
            ->paginate(5);
        $distributeCalls = DistributeCall::where('cskh_id', auth()->id())
            ->with(['orderPackage.customer', 'orderPackage.product_service'])
            ->whereBetween('date_call', [$fromDate, $toDate])
            ->get();

        $orderPackagePlans = [];
        $distributeCalls->each(function ($distributeCall) use (&$orderPackagePlans) {
            $dayOfWeek = Carbon::parse($distributeCall->date_call)->dayOfWeek ? Carbon::parse($distributeCall->date_call)->dayOfWeek : 7;
            if (isset($orderPackagePlans[$distributeCall->order_package_id])) {
                $orderPackagePlans[$distributeCall->order_package_id]['plans']['day_' . $dayOfWeek]['call'] = $distributeCall->state;
            } else {
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

        $orderPackagePlans = collect($orderPackagePlans)->values()->all();

        return Inertia::render('Modules/CustomerService/weekly-plan', compact(
            'orderPackagePlans',
            'remindData'
        ));
    }
}
