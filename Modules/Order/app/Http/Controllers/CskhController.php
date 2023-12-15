<?php

namespace Modules\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Customer\app\Models\HistoryExtend;
use App\Contracts\OrderContract;
use Carbon\Carbon;
use Inertia\Inertia;
use Modules\Order\app\Models\OrderPackage;

class CskhController extends Controller
{
    public function cskh(Request $request){
        // getOrderAll
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $orders  = $this->getOrderAll($request);

        // return $orders;
        return Inertia::render('Modules/Order/Package/cskh', compact('orders', 'from', 'to'));
    }
    public function getOrderAll($request)
    {
        return OrderPackage::with(['customer','ref','package_reviewer', 'product_service','historyPayment.order_package_payment','historyPayment.user','saler','product_service_owner','history_extend.contract.lastcontract.images'])->role()->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')->orwhere('phone_number','%' . $request->search . '%');
            }
        )->fillter($request->only( 'from', 'to', 'payment_status', 'payment_method', 'type'))
        ->orwhere('order_number', 'LIKE', '%' . $request->search . '%')
        ->orwhere('idPackage', 'LIKE', '%' . $request->search . '%')
        ->orderBy('created_at', 'desc')
        ->role()
        ->paginate($request->per_page ? $request->per_page : 5);
    }
}
