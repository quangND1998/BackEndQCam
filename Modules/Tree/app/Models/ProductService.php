<?php

namespace Modules\Tree\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Order\app\Models\ProductServiceVoucher;
use Modules\Order\app\Models\Voucher;
use Modules\Tree\Database\factories\ProductServiceFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductService extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    protected $table = "product_services";
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id", "id_priority", "name", 'status', 'description', "number_tree", "acreage", "free_visit", "amount_products_received", 'price', 'number_deliveries',"number_receive_product", "life_time", "unit"];

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

    // public function owners()
    // {
    //     return $this->belongsToMany(User::class, 'product_service_owners', 'product_service_id', 'user_id');
    // }

    public function images()
    {
        return $this->media()->where('collection_name', 'product_service_images');
    }


    public function vouchers()
    {

        return $this->belongsToMany(Voucher::class, 'product_service_vouchers', 'product_service_id', 'voucher_id');
    }

    // product service co nhieu vouchers
    public function voucher_items()
    {
        return $this->hasMany(ProductServiceVoucher::class, 'product_service_id');
    }
    public function productServiceOwner()
    {
        return $this->hasMany(ProductServiceOwner::class,'product_service_id');
    }
}
