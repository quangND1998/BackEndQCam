<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\ShipingHistory;
use Modules\Tree\app\Models\ProductRetail;

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

        $this->validateContract($request->productServiceOwnerId, $request->customerId);
        $this->validateOrderCondition($request->productServiceOwnerId);
        $data = $this->validateProductCondition((array) $request->products);

        try {
            $customer = User::findOrFail($request->customerId);
            DB::beginTransaction();
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $customer->id,
                'status' => 'pending',
                'item_count' => Collection::make($request->products)->sum('quantity'),
                'payment_status' => 0,
                'grand_total' => 0,
                'discount' => 0,
                'last_price' => 0,
                'name' => $customer->name,
                'address' => $request->address['detail'],
                'city' => $request->address['city'],
                'district' => $request->address['district'],
                'wards' => $request->address['ward'],
                'notes' => $request->note,
                'type' => 'gift_delivery',
                'product_service_owner_id' => $request->productServiceOwnerId,
                'sale_id' => auth()->id(),
                'phone_number' => $request->subPhoneNumber,
                'delivery_no' => $request->deliveryNo,
            ]);

            foreach ($request->products as $product) {
                $order->orderItems()->create([
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                ]);
            }

            ShipingHistory::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'note' => 'Tạo đơn hàng',
            ]);
            $order->load('shipping_history');

            $data['products']->each(function ($product) use ($data) {
                $product->available_quantity -= $data['map'][$product->id];
                $product->save();
            });

            DB::commit();
            return response()->json([
                'message' => 'Ok',
                'order' => $order,
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function validateContract($productServiceOwnerId, $customerId)
    {
        $productServiceOwner = ProductServiceOwner::findOrFail($productServiceOwnerId);
        abort_if($productServiceOwner->user_id != $customerId, 403, 'Sai thông tin khách hàng');
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

    private function validateProductCondition($inputProducs)
    {
        $productIds = Collection::make($inputProducs)->pluck('id')->toArray();
        $productQuantityMap = Collection::make($inputProducs)->keyBy('id')->map(function ($product) {
            return $product['quantity'];
        })->toArray();
        $products = ProductRetail::whereIn('id', $productIds)->get();
        foreach ($products as $product) {
            if ($product->available_quantity < $productQuantityMap[$product->id]) {
                abort(442, 'Số lượng sản phẩm không đủ');
            }
        }

        return [
            'products' => $products,
            'map' => $productQuantityMap,
        ];
    }

    private function updateProductAvailableQuantity()
    {

    }
}
