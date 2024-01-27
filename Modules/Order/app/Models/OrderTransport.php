<?php

namespace Modules\Order\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\OrderTransportFactory;
use Nicolaslopezj\Searchable\SearchableTrait;

class OrderTransport extends Model
{
    use HasFactory, SearchableTrait;
    protected $table = 'order_transports';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['order_transport_number','transport_state','status','order_id'];
    
    protected static function newFactory(): OrderTransportFactory
    {
        //return OrderTransportFactory::new();
    }
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'order_transports.order_transport_number' => 10,
            'orders.order_number' => 8,
            'orders.phone_number' => 8,


        ],
        'joins' => [
            'orders' => ['order_id', 'orders.id'],
        ],

    ];
    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }


    public function scopeFillter($query, array $filters)
    {

        
        if (isset($filters['fromDate']) && isset($filters['toDate'])) {

            $query->whereBetween('created_at', [Carbon::parse($filters['fromDate'])->format('Y-m-d H:i:s'), Carbon::parse($filters['toDate'])->format('Y-m-d H:i:s')]);
        }

     

        if (isset($filters['transport_state'])) {

            $query->where('transport_state', $filters['transport_state']);
        }

       
    }
}
