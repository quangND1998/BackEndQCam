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
use Modules\Tree\app\Models\ProductService;
class CustomerProductOwerController extends Base2Controller
{
   //all product of user
   public function getProductService()
   {
            $customer = Auth::user();
            if($customer){
                // $product_owner = $customer->product_service_owners;
                $product_owner = ProductServiceOwner::with('product.images')->where('user_id',$customer->id)->get();
                $product_not_owner = ProductService::with('images')->whereDoesntHave('productServiceOwner')->get();

                $response = [
                    'user' => $customer->name,
                    'product_owner' =>$product_owner,
                    'not_owner' => $product_not_owner
                ];
                return $this->sendResponse($response, 'Get apartmentDetail successfully');
            }
            return response()->json('Chua login', 200);

   }

   public function getOneProductActivity($id)
   {
        $customer = Auth::user();
        if($customer){
        $product_owner = ProductServiceOwner::with('product.images','trees','history_extend.contract.lastcontract.images','extend_last.contract.lastcontract.images')->where('user_id',$customer->id)->find($id);
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
}
