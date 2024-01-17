<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Reminds;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\CustomerService\app\Models\Remind;

class CreateRemind extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'product_service_owner_id' => 'required|integer|exists:product_service_owners,id',
            'remind_at' => 'required|date|after:today',
            'note' => 'required|string',
        ]);

        $productServiceOwner = ProductServiceOwner::findOrFail($request->product_service_owner_id);
        abort_if($productServiceOwner->user_id != $request->customerId, 442, 'Customer and serivce mismatch');

        $request->merge([
            'user_id' => auth()->id(),
        ]);
        $remind = Remind::create($request->all());

        return response()->json([
            'message' => 'OK',
            'remind' => $remind
        ]);
    }
}
