<?php

namespace Modules\Order\Repositories;

use App\BaseRepository;
use App\Contracts\OrderContract;
use App\Models\User;
use Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderItem;
use Modules\ProductCategory\Entities\Product;
use Modules\Tree\app\Models\ProductRetail;

class OrderRepository implements OrderContract
{

    public function storeOrderDetails($params, $user)
    {
        $order = Order::create([
            'order_number'      =>  'ORD-' . strtoupper(uniqid()),
            'user_id'           => $user->id,
            'status'            =>  'pending',
            'grand_total'       =>  Cart::getSubTotal(),
            'item_count'        =>  Cart::getTotalQuantity(),
            'payment_status'    =>  0,
            'payment_method'    =>  $params['payment_method'],
            'first_name'        =>  $params['first_name'],
            'last_name'         =>  $params['last_name'],
            'address'           =>  $params['address'],

            'notes'             =>  $params['notes']
        ]);

        if ($order) {

            $items = Cart::getContent();

            foreach ($items as $item) {
                // A better way will be to bring the product id with the cart items
                // you can explore the package documentation to send product id with the cart
                $product = ProductRetail::where('name', $item->name)->first();

                $orderItem = new OrderItem([
                    'product_id'    =>  $product->id,
                    'quantity'      =>  $item->quantity,
                    'price'         =>  $item->getPriceSum()
                ]);

                $order->orderItems()->save($orderItem);
            }
        }

        return $order;
    }

    public function listOrders(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
    {
        return Order::all($columns, $order, $sort);
    }

    public function findOrderByNumber($orderNumber)
    {
        return Order::where('order_number', $orderNumber)->first();
    }


    public function getOrder($request, $status)
    {

        // return  Order::with(['customer', 'orderItems.product', 'discount', 'shipper', 'saler'])->role()->whereHas(
        //     'customer',
        //     function ($q) use ($request) {
        //         $q->where('name', 'LIKE', '%' . $request->customer . '%');
        //         $q->orWhere('phone_number', 'LIKE', '%' . $request->customer . '%');
        //     }
        // )->whereHas(
        //     'orderItems.product',
        //     function ($q) use ($request) {
        //         $q->where('name', 'LIKE', '%' . $request->name . '%');
        //     }
        // )->where('status', $status)->fillter($request->only('search', 'fromDate', 'toDate', 'payment_status', 'payment_method', 'type'))->orderBy('created_at', 'desc')->paginate($request->per_page ? $request->per_page : 10);

        return  Order::with(['customer', 'orderItems.product', 'discount', 'shipper', 'saler'])->role()->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer . '%');
                $q->orWhere('phone_number', 'LIKE', '%' . $request->customer . '%');
            }

        )->where('status', $status)->fillter($request->only('search', 'fromDate', 'toDate', 'payment_status', 'payment_method', 'type'))->orderBy('created_at', 'desc')->paginate($request->per_page ? $request->per_page : 10);
    }
    public function getOrderGift($request, $status)
    {
        return  Order::with(['customer', 'product_service.order_package', 'product_service.product', 'orderItems.product', 'shipping_history', 'discount', 'shipper', 'saler'])->role()->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer . '%');
                $q->orWhere('phone_number', 'LIKE', '%' . $request->customer . '%');
            }

        )->where('type', 'gift_delivery')->where('status', $status)->fillter($request->only('search', 'fromDate', 'toDate', 'payment_status', 'payment_method', 'type'))->orderBy('created_at', 'desc')->paginate($request->per_page ? $request->per_page : 10);
    }


    public function groupByOrderStatus()
    {

        $array_status = ['pending', 'packing', 'shipping', 'completed', 'refund', 'decline'];
        $statusGroup = Order::role()->whereHas('orderItems')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        foreach ($array_status as $status) {

            $filtered = $statusGroup->where('status', $status)->first();

            if ($filtered == null) {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => 0,


                );
            } else {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => $filtered->total,

                );
            }
        }
        return $newCollections;
    }



    public function groupByOrderByStatus($array_status)
    {

        $statusGroup = Order::role()->whereHas('orderItems')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        foreach ($array_status as $status) {

            $filtered = $statusGroup->where('status', $status)->first();

            if ($filtered == null) {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => 0,


                );
            } else {

                $newCollections[] = array(
                    'status' => $status,
                    'total' => $filtered->total,

                );
            }
        }
        return $newCollections;
    }


    public function updateOrderRetail($paramas, $order, $user)
    {
        if ($user) {
            $order->update([

                'user_id'           => $user->id,
                'status'            =>  'pending',
                'payment_status'    =>  0,
                'payment_method' =>  $paramas['payment_method'],
                'address' => $paramas['address'],
                'city' => $paramas['city'],
                'district' => $paramas['district'],
                'wards' => $paramas['wards'],
                'phone_number'        => $paramas['phone_number'],
                'grand_total' => Cart::getSubTotalWithoutConditions(),
                'last_price' => Cart::getSubTotal(),
                'item_count' => Cart::getTotalQuantity(),
                'vat' => $paramas['vat'],
                'discount_deal' => $paramas['discount_deal'],
                'type' => $paramas['type'],
                'shipping_fee' => $paramas['shipping_fee'],
                'amount_paid' => $paramas['amount_paid'],
                'receive_at' => $paramas['receive_at'],
                'shipper_id' => $paramas['shipper_id']

            ]);

            $order->amount_unpaid = $order->last_price - $paramas['amount_paid'];
            $order->save();

            if ($order) {

                $items = Cart::getContent();
                $order->orderItems()->delete();
                foreach ($items as $item) {

                    if ($item->quantity > 0) {
                        $orderItem = new OrderItem([
                            'product_id' => $item->id,
                            'quantity'      =>  $item->quantity,
                            'price'         =>  $item->price,
                            'total_price' => $item->getPriceSum(),
                        ]);
                        $order->orderItems()->save($orderItem);
                    }
                }
            }
            return $order;
        }
    }



    public function updateOrderGift($paramas, $order, $user)
    {
        if ($user) {
            $order->update([

                'user_id'           => $user->id,
                'status'            =>  'pending',
                'payment_status'    =>  0,
                'payment_method' =>  $paramas['payment_method'],
                'address' => $paramas['address'],
                'city' => $paramas['city'],
                'district' => $paramas['district'],
                'wards' => $paramas['wards'],
                'phone_number'        => $paramas['phone_number'],
                'grand_total' => 0,
                'last_price' => 0,
                'item_count' => Cart::getTotalQuantity(),
                'vat' => $paramas['vat'],
                'discount_deal' => $paramas['discount_deal'],
                'type' => $paramas['type'],
                'shipping_fee' => $paramas['shipping_fee'],
                'amount_paid' => $paramas['amount_paid'],

                'receive_at' => $paramas['receive_at'],
                'shipper_id' => $paramas['shipper_id']
            ]);

            $order->amount_unpaid = $order->last_price - $paramas['amount_paid'];
            $order->save();

            if ($order) {

                $items = Cart::getContent();
                $order->orderItems()->delete();
                foreach ($items as $item) {

                    if ($item->quantity > 0) {
                        $orderItem = new OrderItem([
                            'product_id' => $item->id,
                            'quantity'      =>  $item->quantity,
                            'price'         =>  $item->price,
                            'total_price' => $item->getPriceSum(),
                        ]);
                        $order->orderItems()->save($orderItem);
                    }
                }
            }
            return $order;
        }
    }


    public function createUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'phone_number' =>  $data['phone_number'],
            'sex' => $data['sex'],
            'address' => $data['address'],
            'city' =>  $data['city'],
            'wards' => $data['wards'],
            'district' =>  $data['district'],

        ]);
        $user->password = Hash::make('cammattroi');
        $user->assignRole('customer');
        $user->save();
        return $user;
    }
}
