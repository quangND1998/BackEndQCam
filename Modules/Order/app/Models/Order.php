<?php

namespace Modules\Order\app\Models;

use App\Enums\OrderDocument;
use App\Models\Payment;
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
use Modules\CustomerService\app\Models\DistributeDate;

class Order extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        "order_number",
        "user_id",
        "status", // create(Tạo mới) - poccessing(Đang xử lý)- completed(Thành công) - pending(Pending)
        "item_count",
        "payment_status",
        "payment_method", // 0 - cash, 1 - bank, 2 - payoo
        "grand_total", // Full price
        "discount",
        "shipping_fee", // in VND
        "last_price", // Price after discount, vat, shipping fee
        "notes",
        "reason",
        'specific_address',
        'address',
        'city',
        'district',
        'vat', // 0 - 100%
        'discount_deal', // 0 - 100%
        'amount_paid',
        'amount_unpaid',
        'type',
        'product_service_owner_id',
        'shipper_id',
        'sale_id',
        'receive_at',
        'status_transport',
        'shipper_status',
        'state_document',
        'state',
        'wards',  "created_at", "updated_at",
        'delivery_no',
        'phone_number',
        'wards',  "created_at", "updated_at",
        'delivery_appointment',
        'order_transport_number',

    ];

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

            $query->where('order_number', 'like', '%' . $filters['search'] . '%')
                ->orWhere('phone_number', 'like', '%' . $filters['search'] . '%');;
        }
        if (isset($filters['fromDate']) && isset($filters['toDate'])) {

            $query->whereBetween('created_at', [Carbon::parse($filters['fromDate'])->format('Y-m-d H:i:s'), Carbon::parse($filters['toDate'])->format('Y-m-d H:i:s')]);
        }

        if (isset($filters['payment_status'])) {

            $query->where('payment_status', $filters['payment_status']);
        }

        if (isset($filters['status'])) {

            $query->where('status', $filters['status']);
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
                $query->whereBetween('updated_at', [Carbon::now()->subMonths(2), Carbon::now()->endOfMonth()]);
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

        // if (!$user->hasPermissionTo('super-admin')) {
        //     if ($user->hasRole('leader-sale')) {
        //         $query->whereIn('sale_id', $user->salers->pluck('id'));
        //     } else {
        //         $query->where('sale_id', $user->id);
        //     }
        // } else {

        //     $query->get();
        // }

        if ($user->hasPermissionTo('super-admin') || $user->hasRole('Kế toán')) {
            $query->get();
        } else {

            if ($user->hasRole('leader-sale')) {
                $query->whereIn('sale_id', $user->salers->pluck('id')->concat([$user->id]));
            } else {
                $query->where('sale_id', $user->id);
            }
        }
    }

    public function payments()
    {
        return $this->hasMay(Payment::class, 'order_id');
    }

    public function last_payment()
    {
        return $this->hasOne(Payment::class, 'order_id')->latest();
    }
    public function shipping_history()
    {
        return $this->hasMany(ShipingHistory::class, 'order_id');
    }


    public function scopeFillterApi($query, array $filters)
    {

        if (isset($filters['search'])) {

            $query->where('order_number', 'like', '%' . $filters['search'] . '%');
        }


        if (isset($filters['shipper_status'])) {
            if ($filters['shipper_status'] == 'addition_document') {
                $query->where('shipper_status', 'delivered')->where('state_document', OrderDocument::not_push);
            } else {
                $query->where('shipper_status', $filters['shipper_status']);
            }
        }

        if (isset($filters['day'])) {

            $query->whereBetween('updated_at', [Carbon::now()->subDay($filters['day']), Carbon::now()]);
        }
        if (isset($filters['date'])) {
            if ($filters['date'] == 'now') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
            } elseif ($filters['date'] == 'yesterday') {
                $yesterday = date("Y-m-d", strtotime('-1 days'));
                $query->whereDate('updated_at', $yesterday);
            } elseif ($filters['date'] == 'month') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } elseif ($filters['date'] == 'beforMonth') {
                $query->whereBetween('updated_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth(1)->endOfMonth()]);
            } else {
                $query->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
            }
        }
    }

    public function scopeFillterTime($query, array $filters)
    {


        if (isset($filters['day'])) {

            $query->whereBetween('updated_at', [Carbon::now()->subDay($filters['day']), Carbon::now()]);
        }
        if (isset($filters['date'])) {
            if ($filters['date'] == 'now') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()]);
            } elseif ($filters['date'] == 'yesterday') {
                $yesterday = date("Y-m-d", strtotime('-1 days'));
                $query->whereDate('updated_at', $yesterday);
            } elseif ($filters['date'] == 'month') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } elseif ($filters['date'] == 'beforMonth') {
                $query->whereBetween('updated_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth(1)->endOfMonth()]);
            }
        }
    }
    public function distributeDate()
    {
        return $this->hasOne(DistributeDate::class, 'order_id');
    }


    public function order_transports()
    {
        return $this->hasMany(OrderTransport::class, 'order_id');
    }
}
