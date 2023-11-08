<?php

namespace Modules\Tree\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\app\Models\OrderItem;
use Modules\Tree\Database\factories\ProductRetailFactory;

class ProductRetail extends Model
{
    use HasFactory;

    protected $table = "product_retails";
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id","id_priority", "name","drescription", 'price'];
    
    protected static function newFactory(): ProductRetailFactory
    {
        //return ProductRetailFactory::new();
    }

      /**
     * Get all of the post's comments.
     */
    public function order_items()
    {
        return $this->morphMany(OrderItem::class, 'orderitemable');
    }
}
