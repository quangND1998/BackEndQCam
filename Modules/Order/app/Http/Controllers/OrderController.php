<?php

namespace Modules\Order\app\Http\Controllers;

use App\Contracts\OrderContract;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\OrderPackingNotification;
use App\Notifications\OrderShippingNotification;
use App\Notifications\ShipperNewOrderNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;
use Modules\Order\app\Models\Order;
use Modules\Tree\app\Models\ProductRetail;
use Cart;
use Modules\Customer\app\Resources\UserResource;
use Modules\Order\app\Http\Requests\OrderGiftPostRequest;
use Modules\Order\app\Http\Requests\SaveOrderRequest;
use Modules\Order\app\Models\OrderItem;
use Illuminate\Support\Facades\Notification;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Landingpage\app\Models\Contact;
use Modules\Order\Repositories\ShipperRepository;
use Modules\Order\app\Http\Requests\Order\UpdateOrderReuquest;
use Modules\Order\app\Http\Requests\OrderGiftUpdateRequest;
use App\Enums\OrderStatusEnum;

class OrderController extends Controller
{
    protected $allowStoreCustomer = [
        "address",    "city",    "district", "wards",    "country"

    ];
    protected $orderRepository, $shipperRepository;
    public function __construct(OrderContract $orderRepository, ShipperRepository $shipperRepository)
    {

        $this->orderRepository = $orderRepository;
        $this->shipperRepository = $shipperRepository;
        // $this->middleware('permission:users-manager', ['only' => ['pending', 'packing', 'shipping', 'completed', 'refund', 'decline']]);
        $this->middleware('permission:order-pending|order-packing|order-shipping|order-completed|order-refund|order-decline', ['only' => ['index']]);
        $this->middleware('permission:add-new-package', ['only' => ['create', 'update']]);
        $this->middleware('permission:order-pending', ['only' => ['pending']]);
        $this->middleware('permission:order-packing', ['only' => ['processing']]);
        $this->middleware('permission:order-shipping', ['only' => ['shipping']]);
        $this->middleware('permission:order-completed', ['only' => ['completed']]);
        $this->middleware('permission:order-refund', ['only' => ['refund']]);
        $this->middleware('permission:order-decline', ['only' => ['decline']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return $request;
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders =  $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();
        $shippers = $this->shipperRepository->getShipper();

        // dd($statusGroup);
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }


    public function pending(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::pending;
        $orders =  $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();
        $shippers = $this->shipperRepository->getShipper();

        // if ($request->wantsJson()) {
        //     return $products;
        // }
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }

    public function create(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::create;
        $orders =  $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();
        $shippers = $this->shipperRepository->getShipper();

        // if ($request->wantsJson()) {
        //     return $products;
        // }
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }

    public function processing(Request $request)
    {

        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::processing;
        $orders = $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();

        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }


    public function shipping(Request $request)
    {


        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'shipping';
        $orders = $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();


        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }

    public function completed(Request $request)
    {


        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::completed;
        $orders = $this->orderRepository->getOrder($request, $status);

        $statusGroup = $this->orderRepository->groupByOrderStatus();


        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }

    public function refund(Request $request)
    {


        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'refund';


        $orders = $this->orderRepository->getOrder($request, $status);

        $statusGroup = $this->orderRepository->groupByOrderStatus();


        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }

    public function draft(Request $request)
    {


        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'draft';


        $orders = $this->orderRepository->getOrder($request, $status);

        $statusGroup = $this->orderRepository->groupByOrderStatus();


        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }


    public function decline(Request $request)
    {


        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'decline';
        $orders = $this->orderRepository->getOrder($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderStatus();


        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render('Modules/Order/OrderWait', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
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


    // public function updateOrder(UpdateOrderRequest $request)
    // {
    //     $order = Order::find($request->order_id);
    //     if ($order->payment_status == 1) {
    //         return Response::json('Đơn hàng đã thanh toán không được sửa', 404);
    //     }
    //     if ($order) {
    //         $fee = $order->grand_total - ($order->grand_total * $request->discount) / 100 + $request->shipping_fee;
    //         $order->shipping_fee = $request->shipping_fee;
    //         $order->discount = $request->discount;
    //         $order->last_price =  $fee;
    //         $order->save();
    //         return response()->json($order->load('customer', 'orderItems.product'), 200);
    //     } else {
    //         return response()->json('Not found', 404);
    //     }
    // }

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
        if ($order->status == 'packing') {
            Notification::send($order->customer, new OrderPackingNotification($order));
        }
        if ($order->status == 'shipping') {
            Notification::send($order->customer, new OrderShippingNotification($order));
        }
        return back()->with('success', 'Đơn hàng đã được chuyển sang trạng thái');
    }

    public function orderChangeShipping(Request $request, Order $order)
    {
        $request->validate([
            'shipper' => 'required',
        ], [
            'shipper.required' => 'Chọn Shipper cho đơn hàng'
        ]);
        $order->update([
            'status' => $request->status,
            'shipper_id' => $request->shipper
        ]);

        if ($order->status == 'shipping') {
            $shipper = User::find($request->shipper);
            Notification::send($order->customer, new OrderShippingNotification($order));
            Notification::send($shipper, new ShipperNewOrderNotification($order));
        }
        return back()->with('success', 'Đơn hàng đã được chuyển sang trạng thái');
    }


    public function orderChangePayment(Request $request)
    {

        $order = Order::findOrFail($request->id);
        $order->update(['payment_status' => $request->payment_status]);
        return back()->with('success', 'Chuyển trạng thái thanh toán thành công');
    }

    public function createOrder(Request $request)
    {

        $customers = User::with(['product_service_owners.trees', 'product_service_owners.product', 'product_service_owners' => function ($q) {
            $q->where('state', 'active');
        }])->role('customer')->get();

        $cart = Cart::getContent();
        $total_price = Cart::getSubTotalWithoutConditions();
        $sub_total = Cart::getSubTotal();
        $product_retails = ProductRetail::with('images')->get();
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render('Modules/Order/Create/CreateOrder', compact('customers', 'product_retails', 'cart', 'total_price', 'sub_total', 'shippers'));
    }

    public function searchUser(Request $request)
    {

        if ($request->search) {
            $customer = User::with(['product_service_owners' => function ($q) {
                $q->where('state', 1);
            }])->role('customer')->where(function ($query) use ($request) {
                $query->where('phone_number', $request->search);
                $query->orwhere('cic_number', $request->search);
                // $query->orwhere('phone', 'LIKE', '%' . $request->term . '%');
            })->first();
            if ($customer) {
                return new UserResource($customer);
            } else {
                return response()->json('Không tìm thấy Khách hàng!', 404);
            }
        } else {
            return response()->json('Không tìm thấy Khách hàng!', 404);
        }
    }



    public function addToCart(Request $request)
    {

        $request->validate([
            'product' => 'required',
            'quantity' => 'required|gt:0',

        ]);
        $product = ProductRetail::find($request->product['id']);

        Cart::add(array(
            'id' => $product->id, // inique row ID
            'name' => $product->name,
            'price' => $product->price,
            'quantity' =>  $request->quantity,
            'attributes' => array([
                'image' => count($product->images) > 0 ? $product->images[0]->original_url : null
            ]),
            // 'conditions' => $saleCondition
        ));
        $response = [
            'cart' => Cart::getContent(),
            'total_price' => Cart::getSubTotalWithoutConditions(),
            'sub_total' =>  Cart::getSubTotal()
        ];
        return response()->json($response, 200);
    }



    public function deleteMultipleItem(Request $request)
    {
        foreach ($request->ids as $id) {
            $item = Cart::get($id);

            if ($item) {
                Cart::remove($item->id);
            }
        }

        $response = [
            'cart' => Cart::getContent(),
            'total_price' => Cart::getSubTotalWithoutConditions(),
            'sub_total' =>  Cart::getSubTotal()
        ];
        return response()->json($response, 200);
    }


    public function  updateCart(Request $request)
    {

        Cart::update($request->product_id, array(
            'quantity' => array(
                'relative' => false,
                'value' => $request->quantity_cart
            ),
        ));

        $respone = [
            'total_price' => Cart::getSubTotal(),
            'item' => Cart::get($request->product_id),
            'total_price' => Cart::getSubTotalWithoutConditions(),
            'sub_total' =>  Cart::getSubTotal()
        ];
        return response()->json($respone, 200);
    }
    public function removeItem(Request $request)
    {
        Cart::remove($request->product_id);
        $response = [
            'cart' => Cart::getContent(),
            'total_price' => Cart::getSubTotalWithoutConditions(),
            'sub_total' =>  Cart::getSubTotal()
        ];
        return response()->json($response, 200);
    }



    public function removeCart()
    {

        Cart::clear();
        Cart::clearCartConditions();
        $response = [
            'cart' => Cart::getContent(),
            'total_price' => Cart::getSubTotalWithoutConditions(),
            'sub_total' =>  Cart::getSubTotal()
        ];
        return response()->json($response, 200);
    }

    // Save Order
    public function saveOrder(SaveOrderRequest $request)
    {

        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {

            $user = $this->orderRepository->createUser($request->only('name', 'phone_number', 'sex', 'address', 'city', 'wards', 'district'));
        }
        if (Cart::isEmpty() || Cart::getTotalQuantity() == 0) {
            return  back()->with('warning', 'Giỏ hàng trống hoặc có số lượng bằng 0');
        }
        $this->addConditionToCart($request);

        if ($user) {
            $order = Order::create([
                'order_number'      =>  'ORD-' . strtoupper(uniqid()),
                'user_id'           => $user->id,
                'status'            =>  'create',
                'payment_status'    =>  0,
                'payment_method' => $request->payment_method,
                'address' => $request->address,
                'city' => $request->city,
                'district' => $request->district,
                'wards' => $request->wards,
                'phone_number'        =>  $request->phone_number,
                'notes'         =>  $request->notes,
                'grand_total' => Cart::getSubTotalWithoutConditions(),
                'last_price' => Cart::getSubTotal(),
                'item_count' => Cart::getTotalQuantity(),
                'vat' => $request->vat,
                'discount_deal' => $request->discount_deal,
                'type' => $request->type,
                'shipping_fee' => $request->shipping_fee,
                'amount_paid' => $request->amount_paid,
                'sale_id' => Auth::user()->id,
                'receive_at' => $request->receive_at,
                'shipper_id' => $request->shipper_id

            ]);

            $order->amount_unpaid = $order->last_price - $request->amount_paid;
            $order->save();

            if ($order) {

                $items = Cart::getContent();

                foreach ($items as $item) {
                    // A better way will be to bring the product id with the cart items
                    // you can explore the package documentation to send product id with the cart
                    // $product = Product::where('name', $item->name)->first();
                    if ($item->quantity > 0) {
                        $orderItem = new OrderItem([
                            'product_id' => $item->id,
                            'quantity'      =>  $item->quantity,
                            'price'         =>  $item->price,
                            'total_price' => $item->getPriceSum(),
                        ]);
                        $order->orderItems()->save($orderItem);
                        $product = ProductRetail::findOrFail($item->id);
                        $product->available_quantity -= $item->quantity;
                        $product->orderd_quantity += $item->quantity;
                        $product->save();
                    }
                }
            }
        }

        foreach ($request->images as $image) {
            $order->addMedia($image)->toMediaCollection('order_related_images');
        }
        Cart::clear();
        Cart::clearCartConditions();
        // if ($order->payment_method == 'cash' || $order->payment_method == 'banking') {

        return  redirect()->route('admin.payment.orderCashBankingPayment', [$order]);
        // }
    }

    public function addConditionToCart($request)
    {
        if ($request->discount_deal) {
            $conditionDiscount = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'DISCOUNT',
                'type' => 'tax',
                'target' => 'subtotal',
                'value' => "-" . $request->discount_deal . '%',
                // 'order' => 1
            ));
            Cart::condition($conditionDiscount);
        }
        if ($request->vat) {
            $conditionVat = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'VAT',
                'type' => 'tax',
                'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                'value' => $request->vat . '%',
            ));
            Cart::condition($conditionVat);
        }

        if ($request->shipping_fee) {
            $conditionShipping = new \Darryldecode\Cart\CartCondition(array(
                'name' => 'SHIPPING',
                'type' => 'tax',
                'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                'value' => $request->shipping_fee,

            ));
            Cart::condition($conditionShipping);
        }



        // if ($request->amount_paid) {
        //     $conditionPaid = new \Darryldecode\Cart\CartCondition(array(
        //         'name' => 'PAID',
        //         'type' => 'tax',
        //         'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
        //         'value' => '-' . $request->amount_paid,

        //     ));
        //     Cart::condition($conditionPaid);
        // }
    }

    public function fetchCart()
    {
        $response = [
            'cart' => Cart::getContent(),
            'total_price' => Cart::getSubTotalWithoutConditions(),
            'sub_total' =>  Cart::getSubTotal()
        ];
        Cart::clear();
        Cart::clearCartConditions();
        return back();
    }


    public function saveOrderGift(OrderGiftPostRequest $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {

            $user = $this->orderRepository->createUser($request->only('name', 'phone_number', 'sex', 'address', 'city', 'wards', 'district'));
        }
        if (Cart::isEmpty() || Cart::getTotalQuantity() == 0) {
            return  back()->with('warning', 'Giỏ hàng trống hoặc có số lượng bằng 0');
        }
        $this->addConditionToCart($request);

        if ($user) {
            $order = Order::create([
                'order_number'      =>  'ORD-' . strtoupper(uniqid()),
                'user_id'           => $user->id,
                'status'            =>  'create',
                'payment_status'    =>  0,
                'payment_method' => null,
                'address' => $request->address,
                'city' => $request->city,
                'district' => $request->district,
                'wards' => $request->wards,
                'phone_number'        =>  $request->phone_number,
                'notes'         =>  $request->notes,
                'grand_total' => Cart::getSubTotalWithoutConditions(),
                'last_price' => 0,
                'item_count' => Cart::getTotalQuantity(),
                'type' => $request->type,
                'product_service_owner_id' => $request->product_service_owner_id,
                'sale_id' => Auth::user()->id,
                'receive_at' => $request->receive_at,
                'shipper_id' => $request->shipper_id
            ]);
            $order->save();
            $product_service_owner = ProductServiceOwner::find($request->product_service_owner_id);
            if ($product_service_owner) {
                $count = $product_service_owner->orders()->count();
                $order->index =  $count;
                $order->save();
            }

            if ($order) {

                $items = Cart::getContent();

                foreach ($items as $item) {
                    // A better way will be to bring the product id with the cart items
                    // you can explore the package documentation to send product id with the cart
                    // $product = Product::where('name', $item->name)->first();
                    if ($item->quantity > 0) {
                        $orderItem = new OrderItem([
                            'product_id' => $item->id,
                            'quantity'      =>  $item->quantity,
                            'price'         =>  $item->price,
                            'total_price' => $item->getPriceSum(),
                        ]);
                        $order->orderItems()->save($orderItem);
                        $product = ProductRetail::findOrFail($item->id);
                        $product->available_quantity -= $item->quantity;
                        $product->orderd_quantity += $item->quantity;
                        $product->save();
                    }
                }
            }
        }

        foreach ($request->images as $image) {
            $order->addMedia($image)->toMediaCollection('order_related_images');
        }
        Cart::clear();
        Cart::clearCartConditions();
        return redirect()->route('admin.orders.pending')->with('success', 'Đã tạo đơn quà');
    }

    public function scanOrderDetail($id)
    {
        $order = Order::with('orderItems.product', 'product_service.product', 'product_service.trees', 'reviews')->find($id);
        $contact = Contact::find(1);
        if ($order) {
            //   return $order;
            return Inertia::render('Modules/Order/QrOrder', compact('contact', 'order'));
        }
        return response()->json('Không tìm thấy đơn hàng', 404);
    }

    public function edit(Request $request, Order $order)
    {

        Cart::clear();
        Cart::clearCartConditions();
        if ($order->payment_status == 1) {
            return redirect()->route('admin.orders.pending')->with('warning', 'Đơn hàng Đã thanh toán không thể cập nhật!');
        }
        if ($order->status == 'pending') {
            $order->load('orderItems.product', 'customer', 'order_related_images');
            if (Cart::isEmpty()) {
                foreach ($order->orderItems as $item) {
                    Cart::add(array(
                        'id' => $item->product->id, // inique row ID
                        'name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' =>  $item->quantity,
                        'attributes' => array([
                            'image' => count($item->product->images) > 0 ? $item->product->images[0]->original_url : null
                        ]),
                        // 'conditions' => $saleCondition
                    ));
                }
            }

            $customers = User::with(['product_service_owners.trees', 'product_service_owners.product', 'product_service_owners' => function ($q) {
                $q->where('state', 'active');
            }])->role('customer')->get();

            $cart = Cart::getContent();
            $total_price = Cart::getSubTotalWithoutConditions();
            $sub_total = Cart::getSubTotal();
            $product_retails = ProductRetail::with('images')->get();
            $shippers = $this->shipperRepository->getShipper();
            return Inertia::render('Modules/Order/Create/UpdateOrder', compact('customers', 'product_retails', 'cart', 'total_price', 'sub_total', 'order', 'shippers'));
        } else {
            return redirect()->route('admin.orders.pending')->with('warning', 'Đơn hàng đã đóng gói hoặc đang vận chuyển không thể cập nhật');
        }
    }


    public function updateOrderRetail(UpdateOrderReuquest $request, Order $order, User $user)
    {


        if ($order->payment_status == 1) {
            return redirect()->route('admin.orders.pending')->with('warning', 'Đơn hàng Đã thanh toán không thể cập nhật!');
        }
        if ($order->status !== 'pending') {
            return redirect()->route('admin.orders.pending')->with('warning', 'Đơn hàng đã đóng gói hoặc đang vận chuyển không thể cập nhật');
        }

        if (Cart::isEmpty() || Cart::getTotalQuantity() == 0) {
            return  back()->with('warning', 'Giỏ hàng trống hoặc có số lượng bằng 0');
        }
        $this->addConditionToCart($request);
        $order_update =  $this->orderRepository->updateOrderRetail($request->all(), $order, $user);

        foreach ($request->images as $image) {
            $order_update->addMedia($image)->toMediaCollection('order_related_images');
        }
        Cart::clear();
        Cart::clearCartConditions();
        if ($order_update->payment_method == 'cash' || $order_update->payment_method == 'banking') {

            return  redirect()->route('admin.payment.orderCashBankingPayment', [$order_update]);
        }
        return redirect()->route('admin.orders.pending')->with('success', 'Đã cập nhật đơn');
    }


    public function updateOrderGift(OrderGiftUpdateRequest $request, Order $order, User $user)
    {

        if ($order->status !== 'pending') {
            return redirect()->route('admin.orders.pending')->with('warning', 'Đơn hàng đã đóng gói hoặc đang vận chuyển không thể cập nhật');
        }

        if (Cart::isEmpty() || Cart::getTotalQuantity() == 0) {
            return  back()->with('warning', 'Giỏ hàng trống hoặc có số lượng bằng 0');
        }
        $this->addConditionToCart($request);
        $order_update =  $this->orderRepository->updateOrderGift($request->all(), $order, $user);

        foreach ($request->images as $image) {
            $order_update->addMedia($image)->toMediaCollection('order_related_images');
        }
        Cart::clear();
        Cart::clearCartConditions();
        return redirect()->route('admin.orders.pending')->with('success', 'Đã cập nhật đơn quà');
    }
}
