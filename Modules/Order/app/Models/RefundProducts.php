<?php

namespace Modules\Order\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\RefundProductsFactory;
use Modules\Tree\app\Models\ProductRetail;

class RefundProducts extends Model
{
    use HasFactory;
    protected $table = 'refund_products';
    protected $fillable = [
        "order_number",
        "name",
        "state",
        "code",
        "time",
        "type",
        "reason",
        "order_transport_number",
        "order_number",
        "order_id",
        'product_id',
        'quantity',
        'unit'


    ];


    protected static function newFactory(): RefundProductsFactory
    {
        //return RefundProductsFactory::new();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductRetail::class, 'product_id');
    }

    public function scopeFillter($query, array $filters)
    {

        if (isset($filters['search']) && isset($filters['search'])) {

            $query->where('order_transport_number', 'like', '%' . $filters['search'] . '%')
                ->orWhere('order_number', 'like', '%' . $filters['search'] . '%');;
        }
        if (isset($filters['fromDate']) && isset($filters['toDate'])) {

            $query->whereBetween('created_at', [Carbon::parse($filters['fromDate'])->format('Y-m-d H:i:s'), Carbon::parse($filters['toDate'])->format('Y-m-d H:i:s')]);
        }

        if (isset($filters['state'])) {

            $query->where('state', $filters['state']);
        }

        if (isset($filters['type'])) {

            $query->where('type', $filters['type']);
        }
    }
}
