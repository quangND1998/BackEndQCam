<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\VoucherFactory;
use Modules\Tree\app\Models\ProductRetail;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'order_items';
    protected $fillable = [
        'id',
        'code',
        'name',
        'description',
        'uses',
        'max_uses',
        'max_uses_user',
        'type',
        'discount_amount',
        "is_fixed",
        "starts_at",
        "expires_at"
    ];
    
    protected static function newFactory(): VoucherFactory
    {
        //return VoucherFactory::new();
    }

    public function products(){
        return $this->belongsToMany(ProductRetail::class,'product_voucher','voucher_id', 'product_id');
    }
}
