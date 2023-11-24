<?php

namespace Modules\Customer\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Modules\Order\app\Models\Order;

class ShipperController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:super-admin', ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        $shippers = User::with('shipper_orders')->where(function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
            $query->orwhere('phone_number', 'LIKE', '%' . $request->search . '%');
            // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
        })->role('shipper')->paginate(15);
        return Inertia::render('Shipper/Index', compact('shippers'));
    }

    public function shipperDetail(Request $request, User $shipper)
    {

        $order_shippers = Order::with('customer', 'order_shipper_images')->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer . '%');
                $q->orwhere('phone_number', 'LIKE', '%' . $request->customer . '%');
            }
        )->where('shipper_id', $shipper->id)->where(function ($query) use ($request) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
            // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
        })->orderBy('updated_at', 'desc')->paginate(15);
        return Inertia::render('Shipper/ShipperDetail', compact('shipper', 'order_shippers'));
    }
}
