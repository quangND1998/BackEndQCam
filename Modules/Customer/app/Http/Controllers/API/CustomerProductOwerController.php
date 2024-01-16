<?php

namespace Modules\Customer\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Controllers\API\Base2Controller;
use Carbon\Carbon;
use Modules\Customer\app\Models\ProductServiceOwner;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\app\Models\HistoryExtend;
use Modules\Order\app\Models\Order;
use Modules\Tree\app\Models\ProductService;
class CustomerProductOwerController extends Base2Controller
{
   //all product of user
   public function getProductService()
   {
            $customer = Auth::user();
            if($customer){
                // $product_owner = $customer->product_service_owners;
                $product_owner = ProductServiceOwner::with('product.images','history_gift','tree.images','tree.history_care')->where('user_id',$customer->id)->get();
                $product_not_owner = ProductService::with('images')->whereDoesntHave('productServiceOwner')->where('status',1)->get();

                $response = [
                    'user' => $customer->name,
                    'product_owner' =>$product_owner,
                    'not_owner' => $product_not_owner
                ];
                return $this->sendResponse($response, 'Get apartmentDetail successfully');
            }
            return response()->json('Chua login', 200);

   }
   public function getProductServiceDetail($id){
        $customer = Auth::user();
        if($customer){
            $product_not_owner = ProductService::with('images')->findOrfail($id);
            $response = [
                'product_detail' =>$product_not_owner
            ];
            return $this->sendResponse($response, 'Get apartmentDetail successfully');
        }
    }
   public function getOneProductActivity($id)
   {
        $customer = Auth::user();
        if($customer){
        $product_owner = ProductServiceOwner::with('product.images','trees','tree.thumb_image','tree.images','history_extend.contract.lastcontract.images','extend_last.contract.lastcontract.images','visit','history_gift')->where('user_id',$customer->id)->find($id);
        $dt = Carbon::now();
        $time = $dt->diffInDays($product_owner->time_end);
        $response = [
            'time_remaining' =>$time,
            'product_detail' =>$product_owner
        ];
        return $this->sendResponse($response, 'Get apartmentDetail successfully');
    }

        return response()->json('Chua login', 200);
   }
   public function getDetailExtendHistory($id)
   {
    $customer = Auth::user();
    if($customer){
        $extend = HistoryExtend::with('contract.lastcontract.images')->findOrFail($id);
        $response = [
            'extend' =>$extend
        ];
        return $this->sendResponse($response, 'Get HistoryExtend successfully');
    }
   }
   public function checkProduct($id){
     $customer = Auth::user();
     $product_owner = ProductServiceOwner::where('user_id',$customer->id)->find($id);
     if($product_owner){
        $response = [
            'success' => true
        ];
        return response()->json($response, 200);
     }
     $response = [
            'success' => false
        ];
        return response()->json($response, 200);
   }
   public function checkOrder($id){
    $customer = Auth::user();
    $order = Order::where('user_id',$customer->id)->find($id);
    if($order){
       $response = [
           'success' => true,
           'data' =>$order
       ];
       return response()->json($response, 200);
    }
    $response = [
           'success' => false
       ];
       return response()->json($response, 200);
  }

}
