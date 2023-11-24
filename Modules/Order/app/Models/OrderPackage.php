<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\OrderPackageFactory;
use Modules\Tree\app\Models\ProductService;
use App\Models\User;
use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OrderPackage extends Model implements HasMedia
{
    use InteractsWithMedia;
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
    public function discount()
    {
        return $this->belongsTo(Voucher::class, 'discount');
    }
    public function scopeFillter($query, array $filters)
    {
        if (isset($filters['search']) && isset($filters['search'])) {

            $query->where('order_number', 'like', '%' . $filters['search'] . '%');
        }
        if (isset($filters['from']) && isset($filters['to'])) {

            $query->whereBetween('created_at', [Carbon::parse($filters['from'])->format('Y-m-d H:i:s'), Carbon::parse($filters['to'])->format('Y-m-d H:i:s')]);
        }

        if (isset($filters['payment_status'])) {

            $query->where('payment_status', $filters['payment_status']);
        }


        if (isset($filters['payment_method'])) {

            $query->where('payment_method', $filters['payment_method']);
        }
        if (isset($filters['type'])) {

            $query->where('type', $filters['type']);
        }
    }
    public function order_package_images()
    {
        return $this->media()->where('collection_name', 'order_package_images');
    }

}
