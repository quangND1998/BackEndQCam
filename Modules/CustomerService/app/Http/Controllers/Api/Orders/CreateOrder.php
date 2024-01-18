<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Order\app\Models\Order;

class CreateOrder extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.ward' => 'required|string',
            'address.detail' => 'required|string',
            'notes' => 'nullable|string',
            'products.*.id' => 'required|integer|exists:product_retails,id',
            'products.*.quantity' => 'required|integer|min:1',
            'subPhoneNumber' => 'nullable|string',
            'productServiceOwnerId' => 'required|integer|exists:product_service_owners,id',
            'deliveryNo' => 'required|integer|min:1',
        ]);

        $this->validateContract($request->productServiceOwnerId);
        $this->validateOrderCondition($request->productServiceOwnerId);

        try {
            DB::beginTransaction();
            $order = Order::create([

            ]);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function validateContract($productServiceOwnerId)
    {
        $productServiceOwner = ProductServiceOwner::findOrFail($productServiceOwnerId);
        abort_if($productServiceOwner->user_id != auth()->id(), 403);
    }

    private function validateOrderCondition($productServiceOwnerId)
    {
        $activeOrderCount = Order::where('product_service_owner_id', $productServiceOwnerId)
            ->whereIn('status', ['pending', 'packing', 'shipping'])
            ->where('type', 'gift_delivery')
            ->count();

        if ($activeOrderCount === 12) {
            abort(442, 'Quá số lượng đơn hàng đang xử lý');
        }
    }
}
