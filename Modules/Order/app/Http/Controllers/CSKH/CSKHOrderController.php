<?php

namespace Modules\Order\app\Http\Controllers\CSKH;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Modules\Order\Repositories\ShipperRepository;
use App\Contracts\OrderContract;
use App\Enums\OrderDocument;
use App\Enums\OrderStatusEnum;
use App\Enums\OrderTransportState;
use App\Enums\ShipperStatusEnum;
use App\Http\Resources\OrderResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Modules\Order\app\Http\Requests\ChangeShipperStatusRequest;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderTransport;
use Modules\Order\app\Models\RefundProducts;
use Modules\Tree\app\Models\ProductRetail;
use Modules\Order\Repositories\OrderTransportRepository;
class CSKHOrderController extends Controller
{
    protected $orderRepository, $shipperRepository, $orderTransportRepository;


    public function __construct(OrderContract $orderRepository, ShipperRepository $shipperRepository,OrderTransportRepository $orderTransportRepository )
    {

        $this->orderRepository = $orderRepository;
        $this->shipperRepository = $shipperRepository;
        $this->orderTransportRepository =$orderTransportRepository;
        // $this->middleware('permission:users-manager', ['only' => ['pending', 'packing', 'shipping', 'completed', 'refund', 'decline']]);
        $this->middleware('permission:order-pending|order-packing|order-shipping|order-completed|order-refund|order-decline', ['only' => ['index']]);

        $this->middleware('permission:order-pending', ['only' => ['pending']]);
        $this->middleware('permission:order-packing', ['only' => ['packing']]);
        $this->middleware('permission:order-shipping', ['only' => ['shipping']]);
        $this->middleware('permission:order-completed', ['only' => ['completed']]);
        $this->middleware('permission:order-refund', ['only' => ['refund']]);
        $this->middleware('permission:order-decline', ['only' => ['decline']]);


        $this->middleware('permission:order-pending', ['only' => ['pushOrder']]);
        $this->middleware('permission:order-packing', ['only' => ['packedOrder']]);

        $this->middleware('permission:order-packing', ['only' => ['packedOrder']]);

        $this->middleware('role:leader-shipper', ['only' => ['shipperOwner']]);
    }

    public function all(Request $request)
    {

        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';

      
        $orders = OrderResource::collection($this->orderRepository->getAllOrderGift($request));
      
        // $order_transports =   $this->orderTransportRepository->getOrdersTransport($request);
        
        // $statusGroup = $this->orderTransportRepository->groupByCount(OrderTransportState::cases(), 'transport_state');
        $shippers = $this->shipperRepository->getShipper();
   
        // return $orders;
        // dd($statusGroup);
        return Inertia::render('Modules/CSKH/Index', compact('orders', 'status', 'from', 'to','shippers'));
    }
    public function index(Request $request)
    {
        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderTransportState::cases(), 'status_transport');
        $shippers = $this->shipperRepository->getShipper();

        
        // dd($statusGroup);
        return Inertia::render('Modules/CSKH/Index', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }

    public function pending(Request $request)
    {
        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::pending;
        $orders =  OrderResource::collection($this->orderRepository->getOrderGift($request, $status));
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderStatusEnum::cases(), 'status');

        $shippers = $this->shipperRepository->getShipper();

        // dd($statusGroup);
        return Inertia::render('Modules/CSKH/Pending', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders'));
    }

    public function packing(Request $request)
    {
        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::packing;
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderStatusEnum::cases(), 'status');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Packed',
            compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }


    public function not_shipper_receive(Request $request)
    {
        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderTransportState::not_shipper_receive;
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderTransportState::cases(), 'status_transport');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/NotShipperReceive',
            compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }
    public function shipping(Request $request)
    {

        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::shipping;
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderStatusEnum::cases(), 'status');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Shipping',
            compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }

    public function completed(Request $request)
    {

        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::completed;
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderStatusEnum::cases(), 'status');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Delivered',
            compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }

    public function refunding(Request $request)
    {

        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::refunding;
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderStatusEnum::cases(), 'status');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Refunding',
            compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }

    public function refund(Request $request)
    {

        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::refund;
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderStatusEnum::cases(), 'status');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Refund',
            compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }

    public function decline(Request $request)
    {

        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderStatusEnum::decline;
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderStatusEnum::cases(), 'status');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Delivered',
            compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }




    public function pushOrder(Request $request)
    {
       

        if ($request->ids && count($request->ids) > 0) {

            $orders = Order::whereIn('id', $request->ids)->get();

            foreach ($orders as $key => $order) {
             
           
                if($order->status ==OrderStatusEnum::pending->value || $order->status ==OrderStatusEnum::create->value  ){
                   
                    if ($order->product_service) {

                        $order->status= OrderStatusEnum::processing;
                        $order->save();
                        $order_package = $order->product_service->order_package;
                        $number = $order->order_transports()->count();
                       
                        OrderTransport::create([
                            'order_id' => $order->id,
                            'order_transport_number'=> $order_package->order_number.'-'. ($number+1).'-'.$order_package->market,
                            'transport_state'=> OrderTransportState::pending
                        ]);
                      
                    }
                }


            }
            return back()->with('success', "Đã đẩy đơn thành công");
        }
        return back()->with('warning', "Hãy chọn select box");
    }


    public function packedOrder(Request $request,)
    {

        if ($request->ids && count($request->ids) > 0) {
            $orders = Order::find($request->ids);
            foreach ($orders as $order) {
                $order->update([
                    'status' => 'packing'
                ]);
                $this->orderRepository->changeTransportStatus($order, OrderTransportState::packed);
            }
            return back()->with('success', "Đã đóng gói thành công");
        }
        return back()->with('warning', "Hãy chọn select box");
    }

    public function shipperOwner(ChangeShipperStatusRequest $request)
    {


        $shipper = User::find($request->shipper_id);

        if ($shipper) {
            if ($request->ids && count($request->ids) > 0) {
                $orders = Order::find($request->ids);

                foreach ($orders as $order) {

                    $this->orderRepository->changeShipperStatus($order, $shipper, ShipperStatusEnum::pending);
                }
                return back()->with('success', "Đã giao shipper thành công công");
            }
            return back()->with('warning', "Hãy chọn select box");
        }
        return back()->with('warning', "Chưa chọn shippers");
    }


    public function orderDecline(Request $request, Order $order)
    {
        $this->validate(
            $request,
            [
                'reason' => 'required',
                'delivery_appointment' => 'nullable|date|after:' . $order->delivery_appointment,

            ],
            [
                'delivery_appointment.after' => 'Ngày giao dự kiến mới phải lớn hơn ngày giao dự kiến cũ'
            ]
        );


        $order->update([
            'status' => OrderStatusEnum::decline,
            'status_transport' => OrderTransportState::decline,
            'shipper_status' => OrderTransportState::decline,
            'reason' => $request->reason,
            'delivery_appointment' => $request->delivery_appointment
        ]);

        return back()->with('success', "Đơn hàng đã hủy");
    }

    public function orderRefunding(Request $request, Order $order)
    {

        $this->validate(
            $request,
            [
                'reason' => 'required',

            ]
        );

        $order->update([
            'status' => OrderStatusEnum::refunding,
            'shipper_status' => OrderTransportState::refunding,
            'reason' => $request->reason,

        ]);
        return back()->with('success', "Đơn hàng đang chuyển về chờ hoàn");
    }

    public function orderRefund(Request $request)
    {

        $this->validate(
            $request,
            [
                'check' => 'required',


            ]
        );

        if ($request->ids && count($request->ids) > 0) {
            $orders = Order::find($request->ids);
            foreach ($orders as $order) {
                $order->update([
                    'status' => OrderStatusEnum::refund,
                    'status_transport' => OrderTransportState::refund,
                    'shipper_status' => OrderTransportState::refund,

                ]);
                if ($request->check) {
                    foreach ($order->orderItems as $item) {
                        $product = ProductRetail::find($item->product_id);
                        if ($product) {
                            RefundProducts::create([
                                'name' => $product->name,
                                'state' => 'pending',
                                'code' => $product->code,
                                'time' => Carbon::now(),
                                // 'type' => $product->type,
                                'reason' => $order->reason,
                                'order_transport_number' => $order->order_transport_number,
                                'order_number' => $order->order_number,
                                'order_id' => $order->id,
                                'product_id' => $product->id

                            ]);
                        }
                    }
                }
            }
            return back()->with('success', "Đã hoàn thành công");
        }
        return back()->with('warning', "Hãy chọn select box");
    }


    public function confirmStateDocument(Request $request)
    {
        if ($request->ids && count($request->ids) > 0) {
            $orders = Order::find($request->ids);
            foreach ($orders as $order) {
                $order->update([
                    'state_document' => OrderDocument::approved,

                ]);
            }
            return back()->with('success', "Đã duyệt hồ sơ thành công");
        }
        return back()->with('warning', "Hãy chọn select box");
    }

    public function updloadImages(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'nullable',
            'images.*' => 'mimes:jpeg,png,jpg',
        ], [
            'images.*' => 'Ảnh phải là định dạng jepg,png,jpg'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }


        if ($request->images) {
            foreach ($request->images as $image) {
                $order->addMedia($image)->toMediaCollection('order_shipper_images');
            }
            $order->update([
                'state_document' => OrderDocument::not_approved
            ]);
        }
        return back()->with('success', 'upload hồ sơ thành công');
    }
}
