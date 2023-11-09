<?php

namespace Modules\Tree\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Tree\Database\factories\ProductServiceFactory;

class ProductService extends Model
{
    use HasFactory;
    protected $table = "product_services";
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id", "id_priority", "name", 'description', "number_tree", "acreage", "free_visit", "amount_products_received", 'price', 'number_deliveries', "life_time", "unit"];

    protected static function newFactory(): ProductServiceFactory
    {
        //return ProductServiceFactory::new();
    }

    /**
     * Get all of the post's comments.
     */
    public function order_items()
    {
        return $this->morphMany(OrderItem::class, 'orderitemable');
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'product_service_owners', 'product_service_id', 'user_id');
    }
}
