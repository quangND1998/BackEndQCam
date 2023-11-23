<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Models\Order;

class ShipperController extends Controller
{
    public function ordersRetailShipper(Request $request)
    {
        $shipper = Auth::user();
        $orders_retail = Order::where('shipper_id', $shipper->id)->where('type', 'retail')->orderBy('created_at', 'desc')->time($request->only('date'))->paginate(15);
        return response()->json($orders_retail, Response::HTTP_OK);
    }

    public function ordersGiftShipper(Request $request)
    {
        $shipper = Auth::user();
        $orders_gift = Order::with('product_service.product', 'orderItems', 'discount')->where('shipper_id', $shipper->id)->where('type', 'gift_delivery')->orderBy('created_at', 'desc')->time($request->only('date'))->paginate(15);
        return response()->json($orders_gift, Response::HTTP_OK);
    }

    public function orderDetail($id)
    {
        $user = Auth::user();
        $orders_detail = Order::with('product_service.product', 'orderItems.product', 'discount', 'customer')->where('shipper_id', $user->id)->find($id);
   
        if ($orders_detail) {
             return response()->json($orders_detail, 200);
        }
        return response()->json('Không tìm thấy', 404);
    }
}
