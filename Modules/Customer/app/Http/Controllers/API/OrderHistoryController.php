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
use Illuminate\Support\Facades\Validator;
use Modules\Order\app\Models\OrderPackage;
use Modules\Tree\app\Models\ProductService;
use Carbon\Carbon;
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
    public function saveOrderPackageFromApp(Request $request){
        $validator = Validator::make($request->only('package_id', 'payment_method'), [
            'package_id' => 'required',
            'payment_method' => 'required'
        ], [
            'payment_method.required' => 'Hãy chọn phương thức thanh toán'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
        $user = Auth::user();
        $time_life  = 0;
        $product_service = ProductService::find($request->package_id);
        if(!$product_service){
            return response()->json('Gói dịch vụ không tồn tại', 404);
        }else{
            $time_life = (int)$this->checkDay($product_service->life_time,$product_service->unit);
        }
        $order = OrderPackage::create([
            'order_number'      =>  'ORD-' . strtoupper(uniqid()),
            'user_id'           => $user->id,
            'status'            =>  'pending',
            'payment_status'    =>  0,
            'payment_method' => $request->payment_method,
            'address' => $user->address,
            'city' => $user->city,
            'district' => $user->district,
            'wards' => $user->wards,
            'phone_number'        =>  $user->phone_number,
            'grand_total' => $product_service->price,
            'type' => 'gift_delivery',
            'product_selected' => $request->package_id,
            'time_end' => Carbon::now()->addDays($time_life),
        ]);
        $response = [
            'success' => true,
            'msg' => "save order package success",
            'data' =>$order,
        ];
        return response()->json($response, 200);
    }

}
