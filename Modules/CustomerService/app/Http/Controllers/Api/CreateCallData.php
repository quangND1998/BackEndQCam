<?php

namespace Modules\CustomerService\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\saveDataCall;
use Illuminate\Http\Request;
use Modules\CustomerService\app\Models\DistributeCall;
use Modules\Order\app\Models\OrderPackage;

class CreateCallData extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'sip_call_id' => 'required|string',
            'customer_id' => 'required|integer|exists:users,id',
        ]);

        $orderPackages = OrderPackage::where('user_id', $request->customer_id)
            ->where('status', 'complete')
            ->get();
        $distributeCalls = DistributeCall::whereIn('order_package_id', [$orderPackages->pluck('id')->toArray()])
            ->whereNotIn('state', ['done', 'remind_call_back'])
            ->where('date_call', date('Y-m-d'))
            ->get();
        if ($distributeCalls->count() > 0) {
            dispatch(new saveDataCall($request->sip_call_id, $distributeCalls->pluck('id')->toArray()))
                ->delay(now()->addMinutes(15));
        }

        return response()->json([
            'message' => 'OK',
        ]);
    }
}
