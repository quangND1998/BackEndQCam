<?php

namespace Modules\Tree\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\app\Models\OrderItem;
use Modules\Order\app\Models\Voucher;
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


    public function vouchers(){
        // return $this->belongsToMany(Voucher::class,'product_voucher', 'product_retail_id','voucher_id');
        return $this->belongsToMany(Voucher::class, 'product_voucher', 'product_retail_id', 'voucher_id');
    }

    public function getHasDiscountAttribute()
    {
      
    //   if(!empty($this->vouchers)){
    //         $check = Carbon::now()->between($this->discount_start,$this->discount_end);
    //   }
    //   return $this->discount;
      
     }
  
      public function getPriceForSellingAttribute()
     {
    //   if($this->HasDiscount) { return ($this->price - ($this->price * $this->discount/100));} ;
    //   return $this->price;
  
     }
  
}
