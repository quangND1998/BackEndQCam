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
        $shipper->load('shipper_orders');
        $order_shippers = Order::with('customer')->where('shipper_id', $shipper->id)->paginate(15);
        return Inertia::render('Shipper/ShipperDetail', compact('shipper', 'order_shippers'));
    }
}
