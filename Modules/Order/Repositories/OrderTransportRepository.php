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
use Modules\Order\app\Models\OrderTransport;
use Modules\ProductCategory\Entities\Product;
use Modules\Tree\app\Models\ProductRetail;

class OrderTransportRepository
{

    public function groupByCount($array_status, $attribute)
    {

        $statusGroup = OrderTransport::select($attribute, DB::raw('count(*) as total'))
            ->groupBy($attribute)
            ->get();

        foreach ($array_status as $status) {
            $filtered = $statusGroup->where($attribute, $status->value)->first();
            if ($filtered == null) {

                $newCollections[] = array(
                    $attribute => $status,
                    'total' => 0,


                );
            } else {

                $newCollections[] = array(
                    $attribute => $status,
                    'total' => $filtered->total,

                );
            }
        }
        return $newCollections;
    }



    public function getOrdersTransport($request)
    {

        return OrderTransport::with(['order', 'order.customer' => function ($q) {
            $q->withCount('orders');
        }, 'order.product_service.product', 'order.orderItems.product', 'order.shipping_history',  'order.shipper', 'order.saler', 'order.product_service.order_package', 'order.order_shipper_images', 'order.saler'])->whereHas(
            'order.product_service.order_package',
            function ($q) use ($request) {
                if ($request->market) {

                    $q->where('market', $request->market);
                }
            }

        )->whereHas('order', function ($q) use ($request) {

            $q->fillter($request->only('type', 'state_document'));
        })->search($request->search, null, true)->fillter($request->only('state', 'fromDate', 'toDate'))->orderBy('created_at', 'desc')->paginate($request->per_page ? $request->per_page : 10);
    }


    public function getOrdersTransportbyState($request, $state)
    {

        return OrderTransport::with(['order', 'order.customer' => function ($q) {
            $q->withCount('orders');
        },  'order.product_service.product', 'order.orderItems.product', 'order.shipping_history', 'order.shipper', 'order.saler', 'order.product_service.order_package', 'order.order_shipper_images', 'order.saler'])->where('order_transports.state', $state)->whereHas(
            'order.product_service.order_package',
            function ($q) use ($request) {
                if ($request->market) {

                    $q->where('market', $request->market);
                }
            }
        )->whereHas('order', function ($q) use ($request) {

            $q->fillter($request->only('type', 'state_document'));
        })->search($request->search, null, true)->fillter($request->only('state',  'fromDate', 'toDate', 'status'))->orderBy('created_at', 'desc')->paginate($request->per_page ? $request->per_page : 10);
    }
}
