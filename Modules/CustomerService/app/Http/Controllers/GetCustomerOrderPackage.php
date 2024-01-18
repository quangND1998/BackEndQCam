<?php

namespace Modules\CustomerService\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\CustomerService\app\Models\VisitExtraService;
use Modules\Order\app\Models\OrderPackage;
use Modules\Tree\app\Models\ProductRetail;

class GetCustomerOrderPackage extends Controller
{
    public function __invoke(Request $request)
    {
        $customerId = $request->customerId;
        $declineOrderPackageCount = OrderPackage::where('user_id', $customerId)
            ->where('status', 'decline')
            ->count();
        $orderPackages = OrderPackage::where('user_id', $customerId)
            ->where('status', 'complete')
            ->with(['product_service'])
            ->with('product_service_owner', function ($query) {
                $query->with('orders', function ($orderQuery) {
                    $orderQuery->orderBy('receive_at', 'desc');
                })->with('visit', function ($visitQuery) {
                    $visitQuery->orderBy('date_time', 'asc');
                });
            })
            ->orderBy('id', 'desc')
            ->get();
        $extraServices = VisitExtraService::all();
        $productRetails = ProductRetail::where('status', 1)->get();
        $customer = $orderPackages->count() ? $orderPackages->first()->customer : User::find($customerId);

        return Inertia::render('Modules/CustomerService/order-package', compact(
            'customerId',
            'orderPackages',
            'declineOrderPackageCount',
            'extraServices',
            'productRetails',
            'customer',
        ));
    }
}
