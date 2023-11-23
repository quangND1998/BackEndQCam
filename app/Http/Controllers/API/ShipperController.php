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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class ShipperController extends Base2Controller
{
    public function ordersRetailShipper(Request $request)
    {
        $shipper = Auth::user();
        $orders_retail = Order::where('shipper_id', $shipper->id)->where('type', 'retail')->orderBy('updated_at', 'desc')->time($request->only('date'))->paginate(15);
        return response()->json($orders_retail, Response::HTTP_OK);
    }

    public function ordersGiftShipper(Request $request)
    {
        $shipper = Auth::user();
        $orders_gift = Order::with('product_service.product', 'orderItems', 'discount')->where('shipper_id', $shipper->id)->where('type', 'gift_delivery')->orderBy('updated_at', 'desc')->time($request->only('date'))->paginate(15);
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

    public function saveImageCompleted(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'images' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        $orders_detail = Order::find($id);
        if($orders_detail){
            foreach ($request->images as $image) {
                $orders_detail->addMediaFromBase64($image['data'])->usingFileName(Str::random(10).'.png')->toMediaCollection('order_shipper_images');
            }
            $orders_detail->status = 'completed';
            $orders_detail->save();
            return response()->json($orders_detail->load('product_service.product', 'orderItems.product', 'discount', 'customer'), 200);
        }
        return response()->json('Không tìm thấy', 404);
    
        
    }
}
