<?php

namespace Modules\Order\app\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Customer\app\Models\ReviewManagement;
use Modules\Order\Database\factories\OrderFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Order extends Model implements HasMedia
{
    use InteractsWithMedia;
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
        'sale_id',
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
        if (isset($filters['fromDate']) && isset($filters['toDate'])) {

            $query->whereBetween('created_at', [Carbon::parse($filters['fromDate'])->format('Y-m-d H:i:s'), Carbon::parse($filters['toDate'])->format('Y-m-d H:i:s')]);
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
            if ($filters['date'] == 'day') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
            } elseif ($filters['date'] == 'week') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filters['date'] == 'month') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } else {
                $query->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
            }
        } else {
            $query->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
        }
    }

    public function order_related_images()
    {
        return $this->media()->where('collection_name', 'order_related_images');
    }

    public function order_shipper_images()
    {
        return $this->media()->where('collection_name', 'order_shipper_images');
    }

    public function saler()
    {
        return $this->belongsTo(User::class, 'sale_id');
    }
    public function scopeRole($query)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('super-admin')) {
            if ($user->hasRole('leader-sale')) {
                $query->whereIn('sale_id', $user->salers->pluck('id'));
            } else {
                $query->where('sale_id', $user->id);
            }
        } else {

            $query->get();
        }
    }
}
