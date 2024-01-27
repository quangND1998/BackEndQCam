<?php

namespace Modules\Order\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\OrderTransportFactory;

class OrderTransport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): OrderTransportFactory
    {
        //return OrderTransportFactory::new();
    }

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }


    public function scopeFillter($query, array $filters)
    {

        if (isset($filters['search']) && isset($filters['search'])) {

            $query->where('order_number', 'like', '%' . $filters['search'] . '%')->orWhere('order_transport_number', 'like', '%' . $filters['search'] . '%')
                ->orWhere('phone_number', 'like', '%' . $filters['search'] . '%');;
        }
        if (isset($filters['fromDate']) && isset($filters['toDate'])) {

            $query->whereBetween('created_at', [Carbon::parse($filters['fromDate'])->format('Y-m-d H:i:s'), Carbon::parse($filters['toDate'])->format('Y-m-d H:i:s')]);
        }

     

        if (isset($filters['status'])) {

            $query->where('transport_state', $filters['transport_state']);
        }

       
    }
}
