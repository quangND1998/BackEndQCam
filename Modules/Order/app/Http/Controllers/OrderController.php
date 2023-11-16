<?php

namespace Modules\Order\app\Http\Controllers;

use App\Contracts\OrderContract;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Order\app\Models\Order;
use Modules\Tree\app\Models\ProductRetail;

class OrderController extends Controller
{
    protected $allowStoreCustomer = [
        "address",    "city",    "district", "wards",    "country"

    ];
    protected $orderRepository;
    public function __construct(OrderContract $orderRepository)
    {

        $this->orderRepository = $orderRepository;

        // $this->middleware('permission:users-manager', ['only' => ['pending', 'packing', 'shipping', 'completed', 'refund', 'decline']]);
        $this->middleware('permission:order-pending', ['only' => ['index', 'pending', 'create']]);
        $this->middleware('permission:order-packing', ['only' => ['index', 'packing']]);
        $this->middleware('permission:order-shipping', ['only' => ['index', 'shipping']]);
        $this->middleware('permission:order-completed', ['only' => ['index', 'completed']]);
        $this->middleware('permission:order-refund', ['only' => ['index', 'refund']]);
        $this->middleware('permission:order-decline', ['only' => ['index', 'decline']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $order = Order::with('discount')->find(1);
        // return $order;
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders =  $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }


    public function pending(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders =  $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }



    public function packing(Request $request)
    {

        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'packing';
        $orders = $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();


        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }


    public function shipping(Request $request)
    {


        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'shipping';
        $orders = $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();


        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }

    public function completed(Request $request)
    {


        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'completed';
        $orders = $this->orderRepository->getOrder($request, $status);

        $statusGroup = $this->orderRepository->groupByOrderStatus();


        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }

    public function refund(Request $request)
    {


        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'refund';


        $orders = $this->orderRepository->getOrder($request, $status);

        $statusGroup = $this->orderRepository->groupByOrderStatus();


        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }




    public function decline(Request $request)
    {


        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'decline';
        $orders = $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();


        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('order::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $order =  $this->orderRepository->storeOrderDetails($request->all(), $user);
        return $order->load('orderItems.product');
    }


    public function updateOrder(UpdateOrderRequest $request)
    {
        $order = Order::find($request->order_id);
        if ($order->payment_status == 1) {
            return Response::json('Đơn hàng đã thanh toán không được sửa', 404);
        }
        if ($order) {
            $fee = $order->grand_total - ($order->grand_total * $request->discount) / 100 + $request->shipping_fee;
            $order->shipping_fee = $request->shipping_fee;
            $order->discount = $request->discount;
            $order->last_price =  $fee;
            $order->save();
            return response()->json($order->load('customer', 'orderItems.product'), 200);
        } else {
            return response()->json('Not found', 404);
        }
    }

    public function orderCancel(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required',

        ], [
            'reason.required' => 'Điền lý do hủy đơn'
        ]);
        $order->update([
            'status' => 'decline',
            'reason' => $request->reason
        ]);

        return back()->with('success', 'Hủy đơn thành công');
    }

    public function orderRefund(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required',
            'grand_total' => 'required',
            'shipping_fee' => 'required|numeric|gte:0|lte:grand_total'
        ], [
            'reason.required' => 'Điền lý do hủy đơn'
        ]);

        $order->update([
            'status' => 'refund',
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Hủy đơn thành công');
    }

    public function orderChangeStatus(Request $request, Order $order)
    {

        $order->update([
            'status' => $request->status,
        ]);
        return back()->with('success', `Đơn hàng đã được chuyển sang trạng thái:$request->status`);
    }


    public function orderChangePayment(Request $request)
    {

        $order = Order::findOrFail($request->id);
        $order->update(['payment_status' => $request->payment_status]);
        return back()->with('success', 'Chuyển trạng thái thanh toán thành công');
    }

    public function createOrder(Request $request)
    {

        $customers = User::role('customer')->get();
        $product_retails = ProductRetail::get();
        return Inertia::render('Modules/Order/Create/CreateOrder', compact('customers', 'product_retails'));
    }
}
