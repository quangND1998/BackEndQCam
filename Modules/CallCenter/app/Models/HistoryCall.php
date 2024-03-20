<?php

namespace Modules\CallCenter\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CallCenter\Database\factories\HistoryCallFactory;
use Modules\CustomerService\app\Models\DistributeCall;

class HistoryCall extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'history_calls';
    protected $fillable = [
        "id", "call_id" ,"sip_call_id","status", "cause", "duration","receive_dest","time_started","time_answered","time_ended","time_ringging","billsec","called_count",
         "direction" ,"recording_url","extension", 'from_number','to_number','note','created_at', 'updated_at'
    ];

    protected static function newFactory(): HistoryCallFactory
    {
        //return HistoryCallFactory::new();
    }
    public function distributeCall()
    {
        return $this->belongsTo(DistributeCall::class, 'distribute_call_id');
    }

}
