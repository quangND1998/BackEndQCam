<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\OrderPackageFactory;
use Modules\Tree\app\Models\ProductService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\app\Models\ProductServiceOwner;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Customer\app\Models\HistoryExtend;
use Modules\CustomerService\app\Models\DistributeDate;

class OrderPackage extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    protected $table = 'order_packages';
    protected $fillable = [
        "id", "idPackage",  "order_number", "user_id", "status", "item_count", "payment_status", "payment_method", "grand_total", "discount", "shipping_fee", "last_price", "notes", "reason", 'specific_address',
        'address',
        'city',
        'district',
        'vat',
        'discount_deal',
        'type',
        'sale_id', 'ref_id', 'to_id', 'customer_resources', 'customer_resources_id', 'package_reviewer',
        'wards',"state_document",  "created_at", "updated_at", "product_selected", "time_approve", "time_end", "price_percent", "time_reservations", "time_expried"
    ];
    protected $appends = ['payment_check', 'exist_accept', 'document_check', 'document_add'];

    public function gettimeExpriedAttribute($value)
    {
        return strtotime($value);
    }

    protected function getUnPaidAttribute()
    {
        return $this->grand_total - $this->price_percent;
    }
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

            $query->orwhere('order_number', 'like', '%' . $filters['search'] . '%')
            ->orwhere('idPackage', 'like', '%' . $filters['search'] . '%');
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
        if (isset($filters['status'])) {

            $query->where('status', $filters['status']);
        }
        if (isset($filters['document_status'])) {
            $query->where('state_document', $filters['document_status']);
        }
    }
    // check tất cả các hồ sơ đã duyệt
    public function getDocumentCheckAttribute()
    {
        if (count($this->historyPayment) > 0) {
            $allHistory = $this->historyPayment->every(function ($history) {
                return $history->state_document == 1;
            });
            return $allHistory;
        }
        return false;
    }
    // check tất cả các thanh toán đã được thêm hồ sơ
    public function getDocumentAddAttribute()
    {
        if (count($this->historyPayment) > 0) {
            $allHistory = $this->historyPayment->every(function ($history) {
                return count($history->order_package_payment) > 0;
            });
            return $allHistory;
        }
        return false;
    }

    public function order_package_images()
    {
        return $this->media()->where('collection_name', 'order_package_images');
    }
    public function historyPayment()
    {
        return $this->hasMany(HistoryPayment::class, 'order_package_id');
    }
    public function complete_historyPayment()
    {
        return $this->historyPayment()->where('status', 'complete');
    }
    public function totalPayment()
    {
        return $this->historyPayment()->sum('amount_received');
    }
    public function scopeRole($query)
    {
        $user = Auth::user();

        if ($user->hasPermissionTo('super-admin') || $user->hasRole('Kế toán')) {
            $query->get();
        } else {
            if ($user->hasRole('leader-sale')) {
                $query->whereIn('sale_id', $user->salers->pluck('id'))
                    ->orWhereIn('ref_id', $user->salers->pluck('id'));
            } else {
                $query->where('sale_id', $user->id)->orwhere('ref_id', $user->id);
            }
        }
    }
    public function saler()
    {
        return $this->belongsTo(User::class, 'sale_id');
    }
    // nhan vien tu van
    public function ref()
    {
        return $this->belongsTo(User::class, 'ref_id');
    }
    // nguoi chot don
    public function leader()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
    // nguon khach hang
    public function resources()
    {
        return $this->belongsTo(User::class, 'customer_resources_id');
    }
    public function package_reviewer()
    {
        return $this->belongsTo(User::class, 'package_reviewer');
    }
    public function product_service_owner()
    {
        return $this->hasOne(ProductServiceOwner::class, 'order_id');
    }
    public function scopePaymenCompleted()
    {
        $allHistory = $this->historyPayment->every(function ($history) {
            return $history->status == "complete";
        });
        // return $allHistory;
    }
    public function getPaymentCheckAttribute()
    {
        if (count($this->historyPayment) > 0) {
            $allHistory = $this->historyPayment->every(function ($history) {
                return $history->status == "complete";
            });
            return $allHistory;
        }
        return false;
    }
    public function getExistAcceptAttribute()
    {
        if (count($this->historyPayment) > 0) {
            $allHistory = $this->historyPayment->every(function ($history) {
                return $history->status != "complete";
            });
            return $allHistory;
        }
        return false;
    }


    public function history_extend()
    {
        return $this->hasOne(HistoryExtend::class, 'order_id');
    }

    public function scopeFilterTime($query, array $filters)
    {

        if (isset($filters['date'])) {

            if ($filters['date'] == 'week') {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filters['date'] == 'beforMonth') {
                $query->whereBetween('created_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth(1)->endOfMonth()]);
            } elseif ($filters['date'] == 'month') {
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } elseif ($filters['date'] == 'year') {
                $query->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
            } else {
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            }
        }

        if (isset($filters['day'])) {

            $query->whereBetween('created_at', [Carbon::now()->subDay($filters['day']), Carbon::now()]);
        }
        if (isset($filters['from']) && isset($filters['to'])) {
            $to = Carbon::parse($filters['to'])->format('Y-m-d H:i:s');
            $from = Carbon::parse($filters['from'])->format('Y-m-d H:i:s');
            $query->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d H:i:s', $from, 'UTC')->setTimezone('+7'), Carbon::createFromFormat('Y-m-d H:i:s', $to, 'UTC')->setTimezone('+7')]);
        }
    }
    public function commissions_packages()
    {
        return $this->hasMany(commissionsPackage::class, 'order_package_id');
    }

    public function distributeDate()
    {
        return $this->hasMany(DistributeDate::class,'order_package_id');
    }
}
