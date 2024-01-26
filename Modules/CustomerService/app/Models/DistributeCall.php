<?php

namespace Modules\CustomerService\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CustomerService\Database\factories\DistributeCallFactory;
use Modules\Order\app\Models\OrderPackage;
class DistributeCall extends Model
{
    use HasFactory;

    protected $table = 'distribute_calls';
    protected $fillable = [
        "id",
        "date_call",
        "state",
        "order_package_id",
        "cskh_id"
    ];
    /**
     * The attributes that are mass assignable.
     */

    protected static function newFactory(): DistributeCallFactory
    {
        //return DistributeCallFactory::new();
    }
    public function orderPackage()
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }
    public function cskh()
    {
        return $this->belongsTo(User::class, 'cskh_id');
    }
}