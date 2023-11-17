<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderItem;
use Modules\Order\app\Models\Voucher;
use Modules\Tree\app\Models\ProductRetail;

class OrderController extends Base2Controller
{
    public function saveOrder(Request $request)
    {

        $validator = Validator::make($request->only('items', 'voucher'), [
            'items' => 'required|array',
            'voucher' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        foreach ($request->items as $item) {
        }

        $order = Order::create([
            'order_number'      =>  'ORD-' . strtoupper(uniqid()),
            'user_id'           => Auth::user()->id,
            'status'            =>  'pending',
            'payment_status'    =>  0,
            'payment_method'    =>  null,
        ]);

        if ($order) {


            $totalPrice = 0;
            foreach ($request->items as $item) {
                // A better way will be to bring the product id with the cart items
                // you can explore the package documentation to send product id with the cart
                $product = ProductRetail::find($item['id']);
                if ($product) {
                    $orderItem = new OrderItem([
                        'product_id'    =>  $product->id,
                        'quantity'      =>  $item['quantity'],
                        'price'         =>  $item['price'],
                        'total_price' =>  $item['price'] * $item['quantity']
                    ]);

                    $order->orderItems()->save($orderItem);
                    $totalPrice = $totalPrice + $orderItem->total_price;
                }
            }
            $order->grand_total = $totalPrice;
            $order->save();
        }

        if ($request->voucher) {
            $voucher = Voucher::find($request->voucher["id"]);
            if ($voucher) {
                $order->discount = $voucher->id;
                $order->save();
            }
            $order->last_price = $order->grand_total - $voucher->discount_mount;
            $order->save();
        } else {
            $order->last_price = $order->grand_total;
            $order->save();
        }
        return $order->load('orderItems.product.images');
    }

    public function getUserOrders(){
        $user= Auth::user();

        $orders= Order::with('orderItems.product', 'discount')->where('status','!=','refund')->orWhere('status','!=','decline')->where('user_id', $user->id)->get();
        return $orders;
    }


   
}
