<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Notifications\OrderPendingNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderItem;
use Modules\Order\app\Models\Voucher;
use Modules\Tree\app\Models\ProductRetail;

class OrderController extends Base2Controller
{
    public function saveOrder(Request $request)
    {

        $validator = Validator::make($request->only('items', 'voucher', 'payment_method'), [
            'items' => 'required|array',
            'voucher' => 'nullable',
            'payment_method' => 'required'
        ], [
            'payment_method.required' => 'Hãy chọn phương thức thanh toán'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        if ($request->voucher) {
            $voucher = Voucher::find($request->voucher["id"]);
            if ($voucher == null) {
                return response()->json('Voucher không còn được sử dụng!', 404);
            } elseif ($voucher->is_fixed == 0) {
                return response()->json('Voucher không còn được sử dụng!', 404);
            } elseif ($voucher->expires_at < Carbon::now()) {
                return response()->json('Voucher đã hết hạn!', 404);
            }
        }

        $user = Auth::user();
        $order = Order::create([
            'order_number'      =>  'ORD-' . strtoupper(uniqid()),
            'user_id'           => Auth::user()->id,
            'status'            =>  'pending',
            'payment_status'    =>  0,
            'payment_method'    =>  $request->payment_method,
            'type' => 'retail',
            'address' => $user->address,
            'city' => $user->city,
            'district' => $user->district,
            'wards' => $user->wards,
            'phone_number' => $user->phone_number,
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
        $order->amount_unpaid = $order->last_price;
        $order->save();
        // Notification::send($order->customer, new OrderPendingNotification($order));
        return $order->load('orderItems.product.images');
    }

    public function getUserOrders()
    {
        $user = Auth::user();

        $orders = Order::with('orderItems.product', 'discount')->where('status', '!=', 'refund')->orWhere('status', '!=', 'decline')->where('user_id', $user->id)->get();
        return $orders;
    }

    public function checkVoucher($data)
    {

        $voucher = Voucher::find($data["id"]);
        if ($voucher == null) {
            return response()->json('Voucher không còn được sử dụng!', 404);
        } elseif ($voucher->is_fixed == 0) {
            return response()->json('Voucher không còn được sử dụng!', 404);
        } elseif ($voucher->expires_at < Carbon::now()) {
            return response()->json('Voucher đã hết hạn!', 404);
        }
    }

    public function orderCompeleted($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update([
                'status' => 'completed'
            ]);
            return response()->json('successfully', 200);
        } else {
            return response()->json('Not found', 404);
        }
    }

    public function orderCancel($id){
        $order = Order::find($id);
        if ($order) {
            $order->update([
                'status' => 'decline'
            ]);
            return response()->json($order, 200);
        } else {
            return response()->json('Not found', 404);
        }

    }
}
