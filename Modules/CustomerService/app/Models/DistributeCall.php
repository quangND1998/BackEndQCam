<?php

namespace Modules\CustomerService\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CallCenter\app\Models\HistoryCall;
use Modules\Order\app\Models\OrderPackage;

class DistributeCall extends Model
{
    use HasFactory;

    protected $table = 'distribute_calls';
    protected $fillable = [
        "id",
        "date_call",
        "state", // pending, dontAnswer, hangup, no_action, done, remind_call_back,
        "order_package_id",
        "cskh_id"
    ];

    public function orderPackage()
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }

    public function cskh()
    {
        return $this->belongsTo(User::class, 'cskh_id');
    }
    public function historycall()
    {
        return $this->hasMany(HistoryCall::class);
    }
}
