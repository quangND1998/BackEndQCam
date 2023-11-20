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
            $products = Order::with('orderItems.product','product_service.product')->where('type','gift_delivery')->where('user_id',$customer->id)->get();
            $productComplete = $products->where('status','!=','completed');
            $productPending = $products->where('status','!=','completed');
            $response = [
                'success' => false,
                'productComplete' => $productComplete,
                'productPending' => $productPending,
            ];
            return response()->json($response, 200);
        }
        return response()->json('Chua login', 200);
    }



}
