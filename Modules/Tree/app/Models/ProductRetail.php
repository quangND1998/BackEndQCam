<?php

namespace Modules\Tree\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\app\Models\OrderItem;
use Modules\Tree\Database\factories\ProductRetailFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductRetail extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $table = "product_retails";
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id", "id_priority", "name", 'code', "outstanding", 'status', "description", 'price'];

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


    public function images()
    {
        return $this->media()->where('collection_name', 'product_retail_images');
    }
}
