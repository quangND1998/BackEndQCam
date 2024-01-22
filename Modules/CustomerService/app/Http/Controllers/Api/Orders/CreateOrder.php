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
use Modules\Order\app\Models\OrderItem;
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
            'subPhoneNumber' => 'nullable|numeric',
            'productServiceOwnerId' => 'required|integer|exists:product_service_owners,id',
            'delivery_appointment' => 'required|date|after:today',
        ]);

        $this->validateContract($request->productServiceOwnerId, $request->customerId);
        $this->validateOrderCondition($request->productServiceOwnerId);
        $data = $this->validateProductCondition((array) $request->products);

        try {
            $customer = User::findOrFail($request->customerId);
            DB::beginTransaction();
            $deliveryNo = Order::where('type', 'gift_delivery')
                ->where('product_service_owner_id', $request->productServiceOwnerId)
                ->max('delivery_no') + 1;
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
                'delivery_no' => $deliveryNo,
                'delivery_appointment' => $request->delivery_appointment,
            ]);


            $orderItems = [];
            foreach ($data['map'] as $productId => $quantity) {
                $orderItems[] = [
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ];
            }
            OrderItem::insert($orderItems);
            $data['products']->each(function ($product) use ($data) {
                $product->available_quantity -= $data['map'][$product->id];
                $product->save();
            });

            ShipingHistory::create([
                'order_id' => $order->id,
                'user_id' => auth()->id(),
                'note' => 'Tạo đơn hàng',
            ]);

            DB::commit();
            $order->load(['shipping_history', 'orderItems.product']);

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
        $productQuantityMap = [];
        Collection::make($inputProducs)->each(function ($product) use (&$productQuantityMap) {
            $productQuantityMap[$product['id']] = isset($productQuantityMap[$product['id']])
                ? $productQuantityMap[$product['id']] + $product['quantity']
                : $product['quantity'];
        });
        $products = ProductRetail::whereIn('id', $productIds)->get();
        foreach ($products as $product) {
            abort_if($product->available_quantity < $productQuantityMap[$product->id], 442, 'Số lượng sản phẩm không đủ');
        }

        return [
            'products' => $products,
            'map' => $productQuantityMap,
        ];
    }
}
