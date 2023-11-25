<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\OrderItemFactory;
use Modules\Tree\app\Models\ProductRetail;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total_price',
        'discount',
        'price_sale',
        'min_spend',
        'created_at',
        "updated_at"
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductRetail::class, 'product_id');
    }

    protected static function newFactory(): OrderItemFactory
    {
        //return OrderItemFactory::new();
    }

}
