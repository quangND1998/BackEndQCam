<?php

namespace Modules\Order\app\Models;

use App\Enums\OrderDocument;
use App\Models\User;
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
    protected $fillable = ['order_transport_number', 'state', 'status', 'user_id', 'order_id'];

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
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function care_staff()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function scopeFillter($query, array $filters)
    {


        if (isset($filters['fromDate']) && isset($filters['toDate'])) {

            $query->whereBetween('created_at', [Carbon::parse($filters['fromDate'])->format('Y-m-d H:i:s'), Carbon::parse($filters['toDate'])->format('Y-m-d H:i:s')]);
        }



        if (isset($filters['state'])) {

            $query->where('state', $filters['state']);
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



    public function scopeFillterApi($query, array $filters)
    {


        if (isset($filters['status'])) {
            if ($filters['status'] == 'addition_document') {
                $query->where('state', 'delivered')->whereHas('order', function ($q) {
                    $q->where('state_document', OrderDocument::not_push);
                });
            } else {
                $query->where('status', $filters['status']);
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
}
