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
use App\Enums\OrderTransportStatus;
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
use Illuminate\Support\Facades\Auth;

class CSKHOrderController extends Controller
{
    protected $orderRepository, $shipperRepository, $orderTransportRepository;


    public function __construct(OrderContract $orderRepository, ShipperRepository $shipperRepository, OrderTransportRepository $orderTransportRepository)
    {

        $this->orderRepository = $orderRepository;
        $this->shipperRepository = $shipperRepository;
        $this->orderTransportRepository = $orderTransportRepository;
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

        $this->middleware('permission:add-order-shipper', ['only' => ['shipperOwner']]);
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
        return Inertia::render('Modules/CSKH/Index', compact('orders', 'status', 'from', 'to', 'shippers'));
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
        $count_orders = OrderTransport::count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderTransportState::pending;
        $order_transports = $this->orderTransportRepository->getOrdersTransportbyState($request, $status);
        $statusGroup = $this->orderTransportRepository->groupByCount(OrderTransportState::cases(), 'state');

        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render('Modules/CSKH/Pending', compact('order_transports', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders'));
    }

    public function packing(Request $request)
    {
        $count_orders = OrderTransport::count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderTransportState::packing;
        $order_transports = $this->orderTransportRepository->getOrdersTransportbyState($request, $status);
        $statusGroup = $this->orderTransportRepository->groupByCount(OrderTransportState::cases(), 'state');

        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render('Modules/CSKH/Packed', compact('order_transports', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders'));
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
        $status = OrderTransportState::shipping;
        $order_transports = $this->orderTransportRepository->getOrdersTransportbyState($request, $status);
        $statusGroup = $this->orderTransportRepository->groupByCount(OrderTransportState::cases(), 'state');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Shipping',
            compact('order_transports', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }

    public function completed(Request $request)
    {

        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderTransportState::delivered;
        $order_transports = $this->orderTransportRepository->getOrdersTransportbyState($request, $status);
        $statusGroup = $this->orderTransportRepository->groupByCount(OrderTransportState::cases(), 'state');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Delivered',
            compact('order_transports', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }

    public function refunding(Request $request)
    {

        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderTransportState::refunding;
        $order_transports = $this->orderTransportRepository->getOrdersTransportbyState($request, $status);
        $statusGroup = $this->orderTransportRepository->groupByCount(OrderTransportState::cases(), 'state');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Refunding',
            compact('order_transports', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }

    public function refund(Request $request)
    {

        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderTransportState::refund;
        $order_transports = $this->orderTransportRepository->getOrdersTransportbyState($request, $status);
        $statusGroup = $this->orderTransportRepository->groupByCount(OrderTransportState::cases(), 'state');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Refund',
            compact('order_transports', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }

    public function decline(Request $request)
    {

        $count_orders = Order::where('state', true)->count();
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderTransportState::decline;
        $order_transports = $this->orderTransportRepository->getOrdersTransportbyState($request, $status);
        $statusGroup = $this->orderTransportRepository->groupByCount(OrderTransportState::cases(), 'state');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render(
            'Modules/CSKH/Decline',
            compact('order_transports', 'status', 'from', 'to', 'statusGroup', 'shippers', 'count_orders')
        );
    }




    public function pushOrder(Request $request)
    {


        if ($request->ids && count($request->ids) > 0) {

            $orders = Order::whereIn('id', $request->ids)->get();

            foreach ($orders as $key => $order) {


                if ($order->status == OrderStatusEnum::pending->value || $order->status == OrderStatusEnum::create->value) {

                    if ($order->product_service) {

                        $order->status = OrderStatusEnum::processing;
                        $order->save();
                        $order_package = $order->product_service->order_package;


                        $order_transport = OrderTransport::create([
                            'order_id' => $order->id,
                            'state' => OrderTransportState::pending,
                            'status' => OrderTransportStatus::wait_package,
                            'delivery_appointment' => $order->delivery_appointment,
                            // 'user_id' => Auth::user()->id
                        ]);
                        $number = OrderTransport::where('order_id', $order->id)->count();

                        $order_transport->order_transport_number = $order_package->idPackage . '-' . ($number) . $order->index . '-' . $order_package->market;
                        $order_transport->save();
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
            $order_transports = OrderTransport::find($request->ids);
            foreach ($order_transports as $order_transport) {
                $order_transport->update([
                    'state' => OrderTransportState::packing,
                    'status' => OrderTransportStatus::not_shipper_owner
                ]);
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
                $order_transports = OrderTransport::find($request->ids);

                foreach ($order_transports as $order_transport) {
                    $order_transport->order->update([
                        'shipper_id' => $shipper->id
                    ]);
                    $order_transport->update([

                        'status' => OrderTransportStatus::not_shipping
                    ]);
                }
                return back()->with('success', "Đã giao shipper thành công ");
            }
            return back()->with('warning', "Hãy chọn select box");
        }
        return back()->with('warning', "Chưa chọn shippers");
    }


    public function orderDecline(Request $request, OrderTransport $order_transport)
    {
        $this->validate(
            $request,
            [
                'reason' => 'required',
                'delivery_appointment' => 'nullable|date|after:' . $order_transport->order->delivery_appointment,

            ],
            [
                'delivery_appointment.after' => 'Ngày giao dự kiến mới phải lớn hơn ngày giao dự kiến cũ',
                'reason.required' => 'Trường lý do hoàn không được bỏ trống'
            ]
        );

        $order_transport->order->update([

            'reason' => $request->reason,
            'delivery_appointment' => $request->delivery_appointment
        ]);
        $order_transport->update([
            'state' => OrderTransportState::decline,
            'status' => OrderTransportStatus::wait_decline,
            'reason' => $request->reason,
        ]);

        return back()->with('success', "Đơn hàng đã hủy");
    }


    public function orderCancel(Request $request)
    {
        if ($request->ids && count($request->ids) > 0) {
            $order_transports = OrderTransport::find($request->ids);
            foreach ($order_transports as $order_transport) {
                if ($order_transport->order->state_document !== OrderDocument::approved) {
                    $order_transport->update([
                        'state' => OrderTransportState::decline,
                        'status' => OrderTransportStatus::decline
                    ]);
                }
            }
            return back()->with('success', "Đơn hàng đang chuyển về chờ hoàn");
        }
        return back()->with('warning', "Hãy chọn select box");
    }

    public function orderRefunding(Request $request)
    {

        $this->validate(
            $request,
            [
                'reason' => 'required',
                'ids' => 'required|array'
            ]
        );
        if ($request->ids && count($request->ids) > 0) {
            $order_transports = OrderTransport::find($request->ids);
            foreach ($order_transports as $order_transport) {
                if ($order_transport->order?->state_document !== OrderDocument::approved->value) {
                    $order_transport->update([
                        'state' => OrderTransportState::refunding,
                        'status' => OrderTransportStatus::wait_refund
                    ]);
                }
            }
            return back()->with('success', "Đơn hàng đang chuyển về chờ hoàn");
        }
        return back()->with('warning', "Hãy chọn select box");
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
            $order_transports = OrderTransport::find($request->ids);
            foreach ($order_transports as $order_transport) {
                if ($order_transport->order->state_document == OrderDocument::not_approved) {
                    $order_transport->order->update([
                        'state_document' => OrderDocument::approved,

                    ]);
                    $order_transport->order->update([
                        'status' => OrderStatusEnum::completed
                    ]);
                }
            }
            return back()->with('success', "Đã duyệt hồ sơ thành công");
        }
        return back()->with('warning', "Hãy chọn select box");
    }

    public function updloadImages(Request $request, OrderTransport $order_transport)
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
                $order_transport->order->addMedia($image)->toMediaCollection('order_shipper_images');
            }
            $order_transport->order->update([
                'state_document' => OrderDocument::not_approved
            ]);
        }
        return back()->with('success', 'upload hồ sơ thành công');
    }
}
