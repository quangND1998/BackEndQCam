<?php

namespace Modules\Order\app\Http\Controllers\API;

use App\Contracts\OrderContract;
use App\Enums\OrderDocument;
use App\Enums\OrderStatusEnum;
use App\Enums\OrderTransportState;
use App\Enums\ShipperStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Order\app\Models\Order;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ApiShipperController extends Controller
{
    public function getOrderStatus(Request $request)
    {
        $user = Auth::user();
        $statusGroup = Order::whereHas('orderItems')->fillterTime($request->only('date', 'day'))->where('shipper_id', $user->id)
            ->select('shipper_status', DB::raw('count(*) as total'))
            ->groupBy('shipper_status')
            ->get();
        $orderNotHaveImages = Order::fillterTime($request->only('date', 'day'))->where('state_document', OrderDocument::not_push)->where('shipper_status', ShipperStatusEnum::delivered)->where('shipper_id', $user->id)
            ->count();
        foreach (ShipperStatusEnum::cases() as $status) {

            $filtered = $statusGroup->where('shipper_status', $status->value)->first();
            if ($filtered == null) {

                $newCollections[] = array(
                    'shipper_status' => $status,
                    'total' => 0,


                );
            } else {

                $newCollections[] = array(
                    'shipper_status' => $status,
                    'total' => $filtered->total,

                );
            }
        }
        $newCollections[]    = [
            'shipper_status' => 'addition_document',
            'total' => $orderNotHaveImages
        ];
        return response()->json($newCollections, 200);
    }

    public function fetchOrders(Request $request)
    {
        $user = Auth::user();
        $orders = Order::with('customer')->where('shipper_id', $user->id)->fillterApi($request->only('search', 'date', 'day', 'shipper_status'))->paginate(15);
        return response()->json($orders, 200);
    }

    public function confirmShipping(Request $request, $id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update([
                'status' => OrderStatusEnum::shipping,
                'shipper_status' => ShipperStatusEnum::shipping,
                'status_transport' => OrderTransportState::delivering
            ]);

            return response()->json($order->load('product_service.product', 'orderItems.product', 'discount', 'customer', 'order_shipper_images'), 200);
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

            return response()->json($order->load('product_service.product', 'orderItems.product', 'discount', 'customer', 'order_shipper_images'), 200);
        } else {
            return response()->json('Không tìm thấy đơn hàng', 404);
        }
    }


    public function confirmCustomerRecive(Request $request, $id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update([
                'status' => OrderStatusEnum::completed,
                'shipper_status' => ShipperStatusEnum::delivered,
                'status_transport' => OrderTransportState::delivered
            ]);
            if ($request->images) {
                foreach ($request->images as $image) {
                    $order->addMedia($image)->toMediaCollection('order_shipper_images');
                }
                $order->update([
                    'state_document' => OrderDocument::not_approved
                ]);
            } else {
                if (count($order->order_shipper_images) == 0) {
                    $order->update([
                        'state_document' => OrderDocument::not_push
                    ]);
                }
            }


            return response()->json($order->load('product_service.product', 'orderItems.product', 'discount', 'customer', 'order_shipper_images'), 200);
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
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        $order = Order::find($id);
        if ($order) {
            if ($order->state_document == OrderDocument::approved) {
                return response()->json('Đơn hàng đã duyệt hồ sơ không thể up thêm ', 404);
            } else {
                if ($request->images) {
                    foreach ($request->images as $image) {
                        $order->addMedia($image)->toMediaCollection('order_shipper_images');
                    }
                    $order->update([
                        'state_document' => OrderDocument::not_approved
                    ]);
                }
            }


            return response()->json($order->load('product_service.product', 'orderItems.product', 'discount', 'customer', 'order_shipper_images'), 200);
        } else {
            return response()->json('Không tìm thấy đơn hàng', 404);
        }
    }


    public function deleteImage(Request $request, $id, $media_id)
    {

        $order = Order::find($id);
        if ($order) {
            if ($order->state_document == OrderDocument::approved) {
                return response()->json('Đơn hàng đã duyệt hồ sơ không thể up thêm ', 404);
            } else {
                $mediaTodelete = Media::where('id', $media_id)->first();
                if ($mediaTodelete) {
                    $order->deleteMedia($media_id);
                    if (count($order->order_shipper_images) == 0) {
                        $order->update([
                            'state_document' => OrderDocument::not_push
                        ]);
                    }
                } else {
                    return response()->json('Không tìm thấy ảnh hồ sơ', 404);
                }
            }


            return response()->json($order->load('product_service.product', 'orderItems.product', 'discount', 'customer', 'order_shipper_images'), 200);
        } else {
            return response()->json('Không tìm thấy đơn hàng', 404);
        }
    }
}
