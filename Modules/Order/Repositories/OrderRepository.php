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
            'payment_method'    =>  null,
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

                $order->items()->save($orderItem);
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


    public function getOrder($filter)
    {
        return Order::whereHas(
            'customer',
            function ($q) use ($filter) {
                $q->where('name', 'LIKE', '%' . $filter['customer'] . '%');

            }
        )->whereHas(
            'orderItems.product',
            function ($q) use ($filter) {
                $q->where('name', 'LIKE', '%' . $filter['name'] . '%');
            }
        )->with(['customer', 'orderItems.product'])->filter($filter)->where(function ($query) use ($filter) {
            $query->where('order_number', 'LIKE', '%' . $filter['term'] . '%');
        })->paginate(10);
    }


 
}
