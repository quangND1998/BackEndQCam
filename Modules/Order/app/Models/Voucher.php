<?php

namespace Modules\Order\app\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\VoucherFactory;
use Modules\Tree\app\Models\ProductRetail;
use Modules\Tree\app\Models\ProductService;

class Voucher extends Model
{
    use HasFactory;
    protected $table = 'vouchers';
    protected $fillable = [
        'id',
        'code',
        'name',
        'description',
        'uses',
        'max_uses',
        'max_uses_user',
        'type',
        'min_spend',
        "discount_caption",
        'discount_percentage',
        'discount_value',
        'discount_max_value',
        'discount_mount',
        'is_fixed',
        "starts_at",
        "expires_at"
    ];

    protected static function newFactory(): VoucherFactory
    {
        //return VoucherFactory::new();
    }

    public function products()
    {
        return $this->belongsToMany(ProductRetail::class, 'product_voucher', 'voucher_id', 'product_retail_id')->withPivot(['price', 'discount', "unit", 'price_sale']);
    }
    /// ma giam gia cho (san pham product) 
    public function product_vouchers()
    {
        return $this->hasMany(ProductVoucher::class, 'voucher_id');
    }

    /// ma giam gia cho goi san pham dichj vu (productService)
    public function product_service_vouchers()
    {
        return $this->hasMany(ProductServiceVoucher::class, 'voucher_id');
    }
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isBefore(now());
    }

    public function scopeFillter($query, array $filters)
    {
        if (isset($filters['total'])) {

            $query->where('min_spend', "<=", $filters['total']);
        }
    }
}
