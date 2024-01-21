<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderItem;
use Modules\Tree\app\Models\ProductRetail;

class UpdateOrder extends Controller
{
    public function __invoke($doNotRemoveThisUnsusedVariable, Order $order, Request $request)
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
        ]);
        abort_if($order->user_id != $request->customerId, 403, 'Sai thông tin khách hàng');
        $data = $this->validateProductCondition($order->orderItems, $request->products);

        try {
            DB::beginTransaction();
            $order->update([
                'item_count' => Collection::make($request->products)->sum('quantity'),
                'name' => $order->customer->name,
                'address' => $request->address['detail'],
                'city' => $request->address['city'],
                'district' => $request->address['district'],
                'wards' => $request->address['ward'],
                'notes' => $request->note,
                'phone_number' => $request->subPhoneNumber,
            ]);

            $order->orderItems()->delete();
            $orderItems = [];
            foreach ($data['map'] as $productId => $quantity) {
                $orderItems[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ];
            }
            $order->orderItems()->createMany($orderItems);
            $data['products']->each(function ($product) use ($data) {
                if (isset($data['quantityChange'][$product->id])) {
                    $product->update([
                        'available_quantity' => $product->available_quantity + $data['quantityChange'][$product->id],
                    ]);
                }
            });

            $order->shipping_history()->create([
                'user_id' => auth()->id(),
                'note' => 'Cập nhật đơn hàng',
            ]);

            DB::commit();
            unset($order->customer);
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

    private function validateProductCondition($orderItem, $inputProducs)
    {
        $productIds = Collection::make($inputProducs)->pluck('id')->toArray();
        $productQuantityMap = [];
        Collection::make($inputProducs)->each(function ($product) use (&$productQuantityMap) {
            $productQuantityMap[$product['id']] = isset($productQuantityMap[$product['id']])
                ? $productQuantityMap[$product['id']] + $product['quantity']
                : $product['quantity'];
        });
        $orderItemMap = [];
        $quanityChange = [];
        $orderItem->each(function($item) use (&$orderItemMap, &$quanityChange) {
            $orderItemMap[$item->product_id] = $item->quantity;
            $quanityChange[$item->product_id] = $item->quantity;
        });
        $products = ProductRetail::whereIn('id',  array_merge($orderItem->pluck('product_id')->toArray(), $productIds))->get();
        foreach ($products as $product) {
            if (!isset($productQuantityMap[$product->id])) {
                continue;
            }

            if (isset($orderItemMap[$product->id])) {
                abort_if($product->available_quantity + $orderItemMap[$product->id] < $productQuantityMap[$product->id], 442, 'Số lượng sản phẩm không đủ');
                $quanityChange[$product->id] = $quanityChange[$product->id] - $productQuantityMap[$product->id];
            } else {
                abort_if($product->available_quantity < $productQuantityMap[$product->id], 442, 'Số lượng sản phẩm không đủ');
                $quanityChange[$product->id] = - $productQuantityMap[$product->id];
            }
        }

        return [
            'products' => $products,
            'map' => $productQuantityMap,
            'quantityChange' => $quanityChange,
        ];
    }
}
