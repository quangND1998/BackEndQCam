<?php

namespace Modules\Order\app\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\OrderFactory;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = ["id",   "order_number","user_id"	,"status" ,"item_count","payment_status","payment_method","grand_total", "discount","shipping_fee","last_price","notes","reason" , 'specific_address',
    'address',
    'city',
    'district',
    'wards',  "created_at", "updated_at"];
 
    
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

    }
}
