<?php

namespace Modules\Order\app\Http\Controllers\CSKH;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Inertia\Inertia;
use Modules\Order\app\Models\OrderPackage;
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
        $results = OrderPackage::with(['customer','product_service','distributeDate','historyPayment.order_package_payment','historyPayment.user','product_service_owner.product','history_extend.contract.lastcontract.images'])->role()->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')->orwhere('phone_number','LIKE','%' . $request->search . '%');
            }
        )
        ->role()
        ->where('status','complete')
        ->orderBy('user_id')->orderBy('created_at', 'desc')
        ->fillter($request->only('search'));

        $orderPackages = $results->paginate($request->per_page ? $request->per_page : 5);
        return $orderPackages;
    }
}
