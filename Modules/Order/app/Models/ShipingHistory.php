<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\ShipingHistoryFactory;

class ShipingHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'shiping_histories';
    protected $fillable = ["id","date","date_end","note","state","user_id","order_id","created_at","updated_at"];

    protected static function newFactory(): ShipingHistoryFactory
    {
        //return ShipingHistoryFactory::new();
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
