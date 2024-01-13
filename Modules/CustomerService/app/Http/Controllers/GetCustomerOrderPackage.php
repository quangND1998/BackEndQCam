<?php

namespace Modules\CustomerService\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Order\app\Models\OrderPackage;

class GetCustomerOrderPackage extends Controller
{
    public function __invoke(Request $request)
    {
        $orderPackages = OrderPackage::where('user_id', $request->customerId)
            ->with(['customer', 'product_service'])
            ->with('product_service_owner', function ($query) {
                $query->with('orders', function ($orderQuery) {
                    $orderQuery->orderBy('receive_at', 'desc');
                })->with('visit', function ($visitQuery) {
                    $visitQuery->orderBy('date_time', 'desc');
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Modules/CustomerService/order-package', compact('orderPackages'));
    }
}
