<?php

namespace Modules\Order\app\Http\Controllers\API;

use App\Contracts\OrderContract;
use App\Enums\OrderDocument;
use App\Enums\OrderStatusEnum;
use App\Enums\OrderTransportState;
use App\Enums\OrderTransportStatus;
use App\Enums\ShipperStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderTransport;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\Order\Repositories\OrderTransportRepository;
use Illuminate\Support\Facades\Cache;

class ApiShipperController extends Controller
{
    protected $orderTransportRepository;
    public function __construct(OrderTransportRepository $orderTransportRepository)
    {

        $this->orderTransportRepository = $orderTransportRepository;
    }
    public function getOrderStatus(Request $request)
    {
        $user = Auth::user();



        $orderNotHaveImages = OrderTransport::whereHas('order', function ($q) use ($user) {
            $q->where('shipper_id', $user->id)->where('state_document', OrderDocument::not_push);
        })->where('state', 'delivered')->fillterTime($request->only('date', 'day'))
            ->count();


        $statusGroup = OrderTransport::whereHas('order', function ($q) use ($user) {
            $q->where('shipper_id', $user->id);
        })->fillterTime($request->only('date', 'day'))->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        $array_status =  [OrderTransportStatus::not_shipping->value, OrderTransportStatus::not_delivered->value, OrderTransportStatus::delivered->value, OrderTransportStatus::wait_refund->value,  OrderTransportStatus::refund->value, OrderTransportStatus::wait_decline->value, OrderTransportStatus::decline->value];
        foreach ($array_status as $status) {
            $filtered = $statusGroup->where('status', $status)->first();
            if ($filtered == null) {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => 0,


                );
            } else {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => $filtered->total,

                );
            }
        }
        $newCollections[]    = [
            'status' => 'addition_document',
            'total' => $orderNotHaveImages
        ];
        return response()->json($newCollections, 200);
    }

    public function fetchOrders(Request $request)
    {
        $user = Auth::user();
        // if (Cache::has('search_' . $request->search)) {
        //     $orders = Cache::get('search_' . $request->search);
        // } else {

        //     $orders = Cache::remember('search_' . $request->search, 30, function () use ($request, $user) {
        //         return OrderTransport::with('order', 'order.customer',  'order.shipper')->whereHas('order', function ($q) use ($user) {
        //             $q->where('shipper_id', $user->id);
        //         })->fillterApi($request->only('date', 'day', 'status', 'search'))->paginate(1)->appends(['page' => $request->page, 'search' => $request->search]);;
        //     });
        // }

        $orders = OrderTransport::with('order', 'order.customer',  'order.shipper')->whereHas('order', function ($q) use ($user) {
            $q->where('shipper_id', $user->id);
        })->fillterApi($request->only('date', 'day', 'status', 'search'))->paginate(10)->appends(['page' => $request->page, 'search' => $request->search]);;

        return response()->json($orders, 200);
    }

    public function confirmShipping(Request $request, $id)
    {
        $order_transport = OrderTransport::find($id);
        if ($order_transport) {
            $order_transport->update([
                'status' => OrderTransportStatus::not_delivered,
                'state' => OrderTransportState::shipping
            ]);

            return response()->json($order_transport->load('order.product_service.product', 'order.orderItems.product', 'order.discount', 'order.customer', 'order.order_shipper_images'), 200);
        } else {
            return response()->json('Không tìm thấy đơn hàng', 404);
        }
    }
    public function confirmNotShipping(Request $request, $id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update([
                'shipper_id' => null,
                'shipper_status' => ShipperStatusEnum::pending,
                'status_transport' => OrderTransportState::not_shipper_receive
            ]);

            return response()->json($order->load('order.product_service.product', 'order.orderItems.product', 'order.discount', 'order.customer', 'order.order_shipper_images'), 200);
        } else {
            return response()->json('Không tìm thấy đơn hàng', 404);
        }
    }


    public function confirmCustomerRecive(Request $request, $id)
    {
        $order_transport = OrderTransport::find($id);
        if ($order_transport) {
            $order_transport->update([
                'state' => ShipperStatusEnum::delivered,
                'status' => OrderTransportStatus::delivered,
            ]);
            if ($request->images) {
                foreach ($request->images as $image) {
                    $order_transport->order->addMedia($image)->toMediaCollection('order_shipper_images');
                }
                $order_transport->order->update([
                    'state_document' => OrderDocument::not_approved
                ]);
            } else {
                if (count($order_transport->order->order_shipper_images) == 0) {
                    $order_transport->order->update([
                        'state_document' => OrderDocument::not_push
                    ]);
                }
            }


            return response()->json($order_transport->load('order.product_service.product', 'order.orderItems.product', 'order.discount', 'order.customer', 'order.order_shipper_images'), 200);
        } else {
            return response()->json('Không tìm thấy đơn hàng', 404);
        }
    }


    public function uploadOrder(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required',
            'images.*' => 'mimes:jpeg,png,jpg',
        ], [
            'images.*' => 'Ảnh phải là định dạng jepg,png,jpg'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $order_transport = OrderTransport::find($id);
        if ($order_transport) {
            if ($order_transport->order->state_document == OrderDocument::approved->value) {
                return response()->json('Đơn hàng đã duyệt hồ sơ không thể xóa hoặc up thêm ', 404);
            } else {
                if ($request->images) {
                    foreach ($request->images as $image) {
                        $order_transport->order->addMedia($image)->toMediaCollection('order_shipper_images');
                    }
                    $order_transport->order->update([
                        'state_document' => OrderDocument::not_approved
                    ]);
                }
            }


            return response()->json($order_transport->load('order.product_service.product', 'order.orderItems.product', 'order.discount', 'order.customer', 'order.order_shipper_images'), 200);
        } else {
            return response()->json('Không tìm thấy đơn hàng', 404);
        }
    }


    public function deleteImage(Request $request, $id, $media_id)
    {

        $order_transport = OrderTransport::find($id);
        if ($order_transport) {
            if ($order_transport->order->state_document == OrderDocument::approved->value) {
                return response()->json('Đơn hàng đã duyệt hồ sơ không thể up thêm ', 404);
            } else {
                $mediaTodelete = Media::where('id', $media_id)->first();
                if ($mediaTodelete) {
                    $order_transport->order->deleteMedia($media_id);
                    if (count($order_transport->order->order_shipper_images) == 0) {
                        $$order_transport->order->update([
                            'state_document' => OrderDocument::not_push
                        ]);
                    }
                } else {
                    return response()->json('Không tìm thấy ảnh hồ sơ', 404);
                }
            }


            return response()->json($order_transport->load('order.product_service.product', 'order.orderItems.product', 'order.discount', 'order.customer', 'order.order_shipper_images'), 200);
        } else {
            return response()->json('Không tìm thấy đơn hàng', 404);
        }
    }



    public function orderTransportDetail($id)
    {
        $user = Auth::user();

        $order_transport = OrderTransport::with('order', 'order.product_service.product', 'order.orderItems.product', 'order.discount', 'order.customer', 'order.order_shipper_images')->whereHas('order', function ($q) use ($user) {
            $q->where('shipper_id', $user->id);
        })->find($id);


        if ($order_transport) {
            return response()->json($order_transport, 200);
        }
        return response()->json('Không tìm thấy', 404);
    }
}
