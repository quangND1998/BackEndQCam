<?php

namespace Modules\Customer\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Order\app\Models\Order;
use App\Contracts\OrderContract;
use App\Notifications\OrderShippingNotification;
use App\Notifications\ShipperNewOrderNotification;
use Illuminate\Support\Facades\Notification;
use Modules\Customer\app\Http\Requests\ShipperOrderRequest;

class ShipperController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-shipper', ['only' => ['index', 'shipperDetail','saveOrderShipper','cancelOrderShipper']]);
    }
    public function index(Request $request)
    {   
        $user= Auth::user();
        if ($user->hasRole('super-admin')) {
            $shippers = User::with('shipper_orders')->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
                $query->orwhere('phone_number', 'LIKE', '%' . $request->search . '%');
                // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
            })->role('shipper')->paginate(15);
        }
        elseif($user->hasRole('leader-shipper')) {
            $shippers = User::with('shipper_orders')->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
                $query->orwhere('phone_number', 'LIKE', '%' . $request->search . '%');
            })->where('created_byId', $user->id)->role('shipper')->paginate(15);
        }
       
        return Inertia::render('Shipper/Index', compact('shippers'));
    }

    public function shipperDetail(Request $request, User $shipper,OrderContract $orderRepository)
    {
        $user= Auth::user();
        $orders =   Order::with(['customer', 'orderItems.product', 'discount', 'shipper', 'saler'])->whereDoesntHave('shipper')->where('status','pending')->where(function($q)use ($request){
            $q->where('order_number', 'like', '%' . $request->search . '%');
        })->where('payment_status',0)->orderBy('created_at', 'desc')->paginate(10);
        if ($request->wantsJson()) {
            return $orders;
        }
       
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
        return Inertia::render('Shipper/ShipperDetail', compact('shipper', 'order_shippers','orders'));
    }

    public function saveOrderShipper(ShipperOrderRequest $request, User $user){
    
        $orders = Order::find($request->orders);
        foreach($orders as $order){
            $order->shipper_id= $user->id;
            $order->save();
            // Notification::send($order->customer, new OrderShippingNotification($order));
            // Notification::send($user, new ShipperNewOrderNotification($order));
        }
        return back()->with('success', 'Phân đơn hàng thanh công');
    }

    public function cancelOrderShipper(Order $order){
        $order->update(['shipper_id'=>null]);
        return back()->with('success', 'Phân đơn hàng thanh công');
    }
}
