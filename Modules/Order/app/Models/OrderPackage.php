<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\OrderPackageFactory;
use Modules\Tree\app\Models\ProductService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
        'sale_id','to_id','customer_resources','customer_resources_id',
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
    public function historyPayment()
    {
        return $this->hasMany(HistoryPayment::class, 'order_package_id');
    }
    public function totalPayment()
    {
        return $this->historyPayment()->sum('amount_received');
    }
    public function scopeRole($query)
    {
        $user = Auth::user();

        if ( $user->hasPermissionTo('super-admin') || $user->hasRole('Kế toán') ) {
            $query->get();
        } else {
            if ($user->hasRole('leader-sale')) {
                $query->whereIn('sale_id', $user->salers->pluck('id'));
            } else {
                $query->where('sale_id', $user->id);
            }
        }
    }
    public function saler()
    {
        return $this->belongsTo(User::class, 'sale_id');
    }
    public function leader()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
    public function resources()
    {
        return $this->belongsTo(User::class, 'customer_resources_id');
    }
}
