<?php

namespace Modules\CustomerService\app\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderPackage;
use Modules\Tree\app\Models\ProductRetail;

class CreateRetailOrder extends Controller
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
            'subPhoneNumber' => 'required|numeric',
            'otherPayment.discount_deal' => 'required|numeric|min:0|max:100',
            'otherPayment.order_code' => 'required|string',
            'otherPayment.payment_method' => 'required|in:0,1,2',
            'otherPayment.shipping_fee' => 'required|numeric|min:0',
            'otherPayment.vat' => 'required|numeric|min:0|max:100',
            'delivery_appointment' => 'required|date|after:today',
        ]);

        $orderPackage = OrderPackage::where('idPackage', $request->otherPayment['order_code'])->first();
        abort_if(!$orderPackage, 442, 'Mã hợp đồng không tồn tại');
        $data = $this->validateProductCondition((array) $request->products);
        $products = $data['products'];
        $map = $data['map'];
        $grandTotal = Collection::make($request->products)->sum(function ($product) use ($map) {
            return $map[$product['id']]['price'] * $product['quantity'];
        });
        $discount = $grandTotal * ($request->otherPayment['discount_deal'] / 100);

        try {
            DB::beginTransaction();
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'item_count' => Collection::make($request->products)->sum('quantity'),
                'payment_status' => 0,
                'grand_total' => $grandTotal,
                'discount' => $discount,
                'last_price' => $grandTotal - $discount + $grandTotal * ($request->otherPayment['vat'] / 100) + $request->otherPayment['shipping_fee'],
                'address' => $request->address['detail'],
                'city' => $request->address['city'],
                'district' => $request->address['district'],
                'wards' => $request->address['ward'],
                'notes' => $request->note,
                'type' => 'retail',
                'sale_id' => auth()->id(),
                'phone_number' => $request->subPhoneNumber,
                'discount_deal' => $request->otherPayment['discount_deal'],
                'product_service_owner_id' => $orderPackage->product_service_owner->id,
                'payment_method' => $request->otherPayment['payment_method'],
                'shipping_fee' => $request->otherPayment['shipping_fee'],
                'vat' => $request->otherPayment['vat'],
                'delivery_appointment' => $request->delivery_appointment,
            ]);
            $order->orderItems()->createMany(Collection::make($map)->map(function ($item, $itemId) {
                return [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                    'product_id' => $itemId,
                ];
            })->toArray());
            $products->each(function ($product) use ($map) {
                $product->available_quantity -= $map[$product->id]['quantity'];
                $product->save();
            });

            DB::commit();

            $order->load('orderItems.product');

            return response()->json([
                'message' => 'Ok',
                'order' => $order,
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function validateProductCondition($inputProducs)
    {
        $productIds = Collection::make($inputProducs)->pluck('id')->toArray();
        $productQuantityMap = [];
        Collection::make($inputProducs)->each(function ($product) use (&$productQuantityMap) {
            $productQuantityMap[$product['id']] = isset($productQuantityMap[$product['id']])
                ? [
                    'quantity' => $productQuantityMap[$product['id']]['quantity'] + $product['quantity'],
                    'price' => 0
                ]
                : [
                    'quantity' => $product['quantity'],
                    'price' => 0
                ];
        });
        $products = ProductRetail::whereIn('id', $productIds)->get();
        foreach ($products as $product) {
            abort_if($product->available_quantity < $productQuantityMap[$product->id]['quantity'], 442, 'Số lượng sản phẩm không đủ');
            $productQuantityMap[$product->id]['price'] = $product->price;
        }

        return [
            'products' => $products,
            'map' => $productQuantityMap,
        ];
    }
}
