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

        return OrderTransport::with('order', 'order.customer', 'order.product_service.product', 'order.orderItems.product', 'order.shipping_history', 'order.discount', 'order.shipper', 'order.saler', 'order.product_service.order_package')->whereHas(
            'order.product_service.order_package',
            function ($q) use ($request) {
                if (isset($request['market'])) {

                    $q->where('market', $request->market);
                }
            }

        )->whereHas('order', function ($q) use ($request){
            if (isset($filters['search']) ) {

                $q->where('order_number', 'like', '%' . $request->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $request->search . '%');;
            }
          
            if (isset($filters['type'])) {
    
                $q->where('type',$request->type);
            }
        })->search($request->search, null, true)->fillter($request->only('transport_state',  'fromDate', 'toDate'))->orderBy('created_at', 'desc')->paginate($request->per_page ? $request->per_page : 10);

  
    }
}
