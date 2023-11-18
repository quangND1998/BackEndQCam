<?php

namespace Modules\Order\Repositories;

use App\BaseRepository;
use App\Contracts\OrderContract;

use Cart;
use Illuminate\Support\Facades\DB;
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
        return Order::with(['customer', 'orderItems.product', 'discount'])->whereHas(
            'customer',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer . '%');
            }
        )->whereHas(
            'orderItems.product',
            function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->name . '%');
            }
        )->where('status', $status)->fillter($request->only('search', 'from', 'to', 'payment_status', 'payment_method', 'type'))->orderBy('created_at', 'desc')->paginate(10);
    }


    public function groupByOrderStatus()
    {

        $array_status = ['pending', 'packing', 'shipping', 'completed', 'refund', 'decline'];
        $statusGroup = Order::whereHas('orderItems')
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
}
