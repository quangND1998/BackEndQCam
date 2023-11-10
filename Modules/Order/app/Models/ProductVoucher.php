<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\ProductVoucherFactory;
use Modules\Tree\app\Models\ProductRetail;

class ProductVoucher extends Model
{
    use HasFactory;
    protected $table = 'product_voucher';
    protected $fillable = [
        'id', 'product_retail_id', 'voucher_id'

    ];


    public function product()
    {
        return $this->belongsTo(ProductRetail::class, 'product_retail_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    protected static function newFactory(): ProductVoucherFactory
    {
        //return ProductVoucherFactory::new();
    }
}
