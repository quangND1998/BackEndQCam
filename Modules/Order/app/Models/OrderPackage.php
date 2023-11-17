<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\OrderPackageFactory;
use Modules\Tree\app\Models\ProductService;
use App\Models\User;
class OrderPackage extends Model
{
    use HasFactory;
    protected $table = 'order_packages';
    protected $fillable = [
        "id",   "order_number", "user_id", "status", "item_count", "payment_status", "payment_method", "grand_total", "discount", "shipping_fee", "last_price", "notes", "reason", 'specific_address',
        'address',
        'city',
        'district',
        'vat',
        'discount_deal',
        'type',
        'wards',  "created_at", "updated_at", "product_selected", "time_approve", "time_end", "price_percent","time_reservations"
    ];
    /**
     * The attributes that are mass assignable.
     */

    protected static function newFactory(): OrderPackageFactory
    {
        //return OrderPackageFactory::new();
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function product_service()
    {
        return $this->belongsTo(ProductService::class, 'product_selected');
    }

}
