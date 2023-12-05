<?php

namespace Modules\Order\app\Models;

use App\Models\User;
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
        "id", "order_package_id" ,"payment_method", "amount_received", "payment_date", "status" ,"note", 'user_id'
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
}
