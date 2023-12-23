<?php

namespace Modules\Order\app\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\HistoryPaymentFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HistoryPayment extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'history_payments';
    protected $fillable = [
        "id", "order_package_id" ,"payment_method", "amount_received", "payment_date",
         "status" ,"note","state_document", 'user_id','user_reviewer_id','created_at', 'updated_at'
    ];

    protected static function newFactory(): HistoryPaymentFactory
    {
        //return HistoryPaymentFactory::new();
    }
    public function orderPackage()
    {
        return $this->belongsTo(OrderPackage::class,'order_package_id');
    }
    public function order_package_payment()
    {
        return $this->media()->where('collection_name', 'order_package_payment');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function user_reviewer(){
        return $this->belongsTo(User::class,'user_reviewer_id');
    }
    public function scopeFilterTime($query, array $filters)
    {

        if (isset($filters['date'])) {

            if ($filters['date'] == 'week') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filters['date'] == 'beforMonth') {

                $query->whereBetween('updated_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth(1)->endOfMonth()]);
            } elseif ($filters['date'] == 'month') {


                $query->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } elseif ($filters['date'] == 'year') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
            } else {

                $query->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            }
        }

        if (isset($filters['day'])) {

            $query->whereBetween('updated_at', [Carbon::now()->subDay($filters['day']), Carbon::now()]);
        }
        if (isset($filters['from']) && isset($filters['to'])) {
            $to= Carbon::parse($filters['to'])->format('Y-m-d H:i:s');
            $from= Carbon::parse($filters['from'])->format('Y-m-d H:i:s');
            $query->whereBetween('updated_at', [ Carbon::createFromFormat('Y-m-d H:i:s', $from, 'UTC')->setTimezone('+7'), Carbon::createFromFormat('Y-m-d H:i:s', $to, 'UTC')->setTimezone('+7')]);
        }
    }
}
