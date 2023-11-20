<?php

namespace Modules\Customer\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Order\app\Models\Order;
use App\Http\Controllers\API\Base2Controller;
use Modules\Customer\app\Models\ProductServiceOwner;
class OrderHistoryController extends Base2Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getListOrderRetail()
    {
        $customer = Auth::user();
        if($customer){
            $products = Order::with('reviews','orderItems.product','product_service.product')->where('type','retail')->where('user_id',$customer->id)->paginate(20);
            return  $products;

            return $this->sendResponse($products, 'san pham le');
        }
        return response()->json('Chua login', 200);
    }
    public function getListGift()
    {
        $customer = Auth::user();
        if($customer){
            $orders = Order::with('orderItems.product','product_service.product')->where('type','gift_delivery')->where('user_id',$customer->id)->get();
            $orderComplete = [];
            $orderPending = [];
            $index = 0;
            $index2 = 0;
            foreach($orders as $order){
                if($order->status == "completed"){
                    $orderComplete[$index] = $order;
                    $index++;
                }else{
                    $orderPending[] = $order;
                    $index2++;
                }

            }
            $response = [
                'success' => true,
                'productComplete' =>$orderComplete,
                'orderPending' => $orderPending,
            ];
            return response()->json($response, 200);
        }
        return response()->json('Chua login', 200);
    }
    public function getHistoryGift($id){
        $customer = Auth::user();
        if($customer){
            $orders = Order::with('orderItems.product','product_service.product','reviews')->where('type','gift_delivery')->where('product_service_owner_id',$id)->where('user_id',$customer->id)->get();
            $orderComplete = [];
            $orderPending = [];
            $index = 0;
            $index2 = 0;
            foreach($orders as $order){
                if($order->status == "completed"){
                    $orderComplete[$index] = $order;
                    $index++;
                }else{
                    $orderPending[] = $order;
                    $index2++;
                }
            }
            $response = [
                'success' => true,
                'productComplete' =>$orderComplete,
                'orderPending' => $orderPending,
            ];
            return response()->json($response, 200);
        }
        return response()->json('Chua login', 200);
    }
    public function orderDetail($id){
         $order = Order::with('orderItems.product','product_service.product','reviews')->findOrFail($id);
         $response = [
                'success' => true,
                'data' =>$order,
            ];
            return response()->json($response, 200);
    }

}
