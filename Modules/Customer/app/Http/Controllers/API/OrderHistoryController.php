<?php

namespace Modules\Customer\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Order\app\Models\Order;
use App\Http\Controllers\API\Base2Controller;
class OrderHistoryController extends Base2Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getListOrderRetail()
    {
        $customer = Auth::user();
        if($customer){
            $products = Order::with('orderItems.product','product_service.product')->where('type','retail')->where('user_id',$customer->id)->paginate(20);
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
            $orderPending = $orders->where('status','!=','completed');
            $index = 0;
            foreach($orders as $order){
                if($order->status == "completed"){
                    $orderComplete[$index] = $order;
                    $index++;
                }

            }
            $response = [
                'success' => true,
                'productComplete' => json_encode($orderComplete),
                'orderPending' => $orderPending,
            ];
            return response()->json($response, 200);
        }
        return response()->json('Chua login', 200);
    }



}
