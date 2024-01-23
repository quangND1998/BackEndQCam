<?php

namespace Modules\CustomerService\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Customer\app\Models\ComplaintManagement;
use Modules\Customer\app\Models\ScheduleVisit;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderPackage;

class GetRecentActivity extends Controller
{
    public function __invoke($customerId)
    {
        $activePackages = OrderPackage::where('user_id', $customerId)
            ->where('status', 'complete')
            ->with(['product_service_owner'])
            ->get();

        $latestOrders = Order::where('user_id', $customerId)
            ->limit(3)
            ->latest()
            ->get();
        $latestVisits = ScheduleVisit::whereIn('product_service_owner_id', $activePackages->pluck('product_service_owner.id')->toArray())
            ->limit(3)
            ->latest()
            ->get();
        $latestComplaints = ComplaintManagement::where('user_id', $customerId)
            ->limit(3)
            ->latest()
            ->get();

        return response()->json([
            'latestOrders' => $latestOrders,
            'latestVisits' => $latestVisits,
            'latestComplaints' => $latestComplaints,
        ]);
    }
}
