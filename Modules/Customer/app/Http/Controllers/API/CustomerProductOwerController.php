<?php

namespace Modules\Customer\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Controllers\API\BaseController;
use Modules\Customer\app\Models\ProductServiceOwner;

class CustomerProductOwerController extends BaseController
{
   public function getProductService()
   {
        // $token = PersonalAccessToken::findToken(request()->bearerToken());
        // $customer = $token->tokenable;
        $customer = User::find(22);
        // $product_owner = $customer->product_service_owners;
        $product_owner = ProductServiceOwner::with('product.images','trees','contract','history_use_service')->where('user_id',$customer->id)->get();
        $response = [
            'user' => $customer->name,
            'product_owner' =>$product_owner
        ];
        return $this->sendResponse($response, 'Get apartmentDetail successfully');

   }
}
