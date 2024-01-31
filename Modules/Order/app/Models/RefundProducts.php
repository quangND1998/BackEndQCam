<?php

namespace Modules\Order\app\Models;

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
}
