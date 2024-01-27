<?php

namespace Modules\CustomerService\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\CustomerService\app\Models\DistributeCall;
use Modules\CustomerService\app\Models\Remind;

class GetWeeklyPlan extends Controller
{
    public function __invoke(Request $request)
    {
        $remindData = Remind::where('user_id', auth()->id())
            ->with('productServiceOwner', function ($query) {
                $query->with(['order_package.product_service', 'customer']);
            })
            ->where('remind_at', '>=', now()->toDateString())
            ->paginate(5);
        $distributeCalls = DistributeCall::where('cskh_id', auth()->id())
            ->with(['orderPackage.customer', 'orderPackage.product_service'])
            ->whereBetween('date_call', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
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
        $orderPackagePlans = collect($orderPackagePlans)->values()->all();

        return Inertia::render('Modules/CustomerService/weekly-plan', compact(
            'orderPackagePlans',
            'remindData'
        ));
    }
}
