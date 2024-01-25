<?php

namespace Modules\Order\app\Http\Controllers\CSKH;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Modules\Order\Repositories\ShipperRepository;
use App\Contracts\OrderContract;
use App\Enums\OrderTransportStatus;
use App\Enums\ShipperStatusEnum;
use App\Models\User;
use Inertia\Inertia;
use Modules\Order\app\Http\Requests\ChangeShipperStatusRequest;
use Modules\Order\app\Models\Order;

class CSKHOrderController extends Controller
{
    protected $orderRepository, $shipperRepository;


    public function __construct(OrderContract $orderRepository, ShipperRepository $shipperRepository)
    {

        $this->orderRepository = $orderRepository;
        $this->shipperRepository = $shipperRepository;
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
        $orders =  $this->orderRepository->getAllOrderGift($request);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderTransportStatus::cases(), 'status_transport');
        $shippers = $this->shipperRepository->getShipper();

        // return $orders;
        // dd($statusGroup);
        return Inertia::render('Modules/CSKH/Index', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }
    public function index(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = 'pending';
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderTransportStatus::cases(), 'status_transport');
        $shippers = $this->shipperRepository->getShipper();

        // return $orders;
        // dd($statusGroup);
        return Inertia::render('Modules/CSKH/Index', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }

    public function pending(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderTransportStatus::pending;
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderTransportStatus::cases(), 'status_transport');
        $shippers = $this->shipperRepository->getShipper();

        // return $orders;
        // dd($statusGroup);
        return Inertia::render('Modules/CSKH/Pending', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }
    public function packing(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderTransportStatus::packing;
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderTransportStatus::cases(), 'status_transport');
        $shippers = $this->shipperRepository->getShipper();

        // return $orders;
        // dd($statusGroup);
        return Inertia::render('Modules/CSKH/Packing', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }
    public function packed(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');
        $status = OrderTransportStatus::packed;
        $orders =  $this->orderRepository->getOrderGift($request, $status);
        $statusGroup = $this->orderRepository->groupByOrderByStatus(OrderTransportStatus::cases(), 'status_transport');
        $shippers = $this->shipperRepository->getShipper();
        return Inertia::render('Modules/CSKH/Shipper', compact('orders', 'status', 'from', 'to', 'statusGroup', 'shippers'));
    }
    public function pushOrder(Request $request)
    {


        if ($request->ids && count($request->ids) > 0) {
            $orders = Order::find($request->ids);
            foreach ($orders as $order) {
                $this->orderRepository->changeTransportStatus($order, OrderTransportStatus::packing);
                return back()->with('success', "Đã đẩy đơn thành công");
            }
        }
        return back()->with('warning', "Hãy chọn select box");
    }


    public function packedOrder(Request $request,)
    {

        if ($request->ids && count($request->ids) > 0) {
            $orders = Order::find($request->ids);
            foreach ($orders as $order) {
                $this->orderRepository->changeTransportStatus($order, OrderTransportStatus::packed);
                return back()->with('success', "Đã đóng gói thành công");
            }
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
}
