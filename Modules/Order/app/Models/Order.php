<?php

namespace Modules\Order\app\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Customer\app\Models\ReviewManagement;
use Modules\Order\Database\factories\OrderFactory;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        "id",   "order_number", "user_id", "status", "item_count", "payment_status", "payment_method", "grand_total", "discount", "shipping_fee", "last_price", "notes", "reason", 'specific_address',
        'address',
        'city',
        'district',
        'vat',
        'discount_deal',
        'amount_paid',
        'amount_unpaid',
        'type',
        'product_service_owner_id',
        'shipper_id',
        'wards',  "created_at", "updated_at"
    ];


    protected static function newFactory(): OrderFactory
    {
        //return OrderFactory::new();
    }


    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
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

    public function discount()
    {
        return $this->belongsTo(Voucher::class, 'discount');
    }


    public function reviews()
    {
        return $this->hasOne(ReviewManagement::class, 'order_id');
    }
    public function product_service()
    {
        return $this->belongsTo(ProductServiceOwner::class, 'product_service_owner_id');
    }

    public function shipper()
    {
        return $this->belongsTo(User::class, 'shipper_id');
    }

    public function scopeTime($query, $filters)
    {
        if (isset($filters['date'])) {

            $query->whereBetween('created_at', [Carbon::parse($filters['from'])->format('Y-m-d H:i:s'), Carbon::parse($filters['to'])->format('Y-m-d H:i:s')]);
        }
    }
}
