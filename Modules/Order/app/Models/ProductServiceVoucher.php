<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\ProductServiceVoucherFactory;
use Modules\Tree\app\Models\ProductService;

class ProductServiceVoucher extends Model
{
    use HasFactory;
    protected $table = 'product_service_vouchers';
    protected $fillable = [
        'id', 'product_service_id', 'voucher_id', 'quantity', 'price', 'discount', "unit", 'price_sale',

    ];


    public function product_service()
    {
        return $this->belongsTo(ProductService::class, 'product_service_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }


    protected static function newFactory(): ProductServiceVoucherFactory
    {
        //return ProductServiceVoucherFactory::new();
    }
}
