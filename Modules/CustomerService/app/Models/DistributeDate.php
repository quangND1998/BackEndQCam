<?php

namespace Modules\CustomerService\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CustomerService\Database\factories\DistributeDateFactory;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Order\app\Models\OrderPackage;

class DistributeDate extends Model
{
    use HasFactory;


    protected $table = 'distribute_dates';
    protected $fillable = [
        "id",
        "date_recevie",
        "state",
        "index_number",
        "order_package_id",
        "product_service_owner_id",
        "order_id"
    ];

    protected static function newFactory(): DistributeDateFactory
    {
        //return DistributeDateFactory::new();
    }
    public function productServiceOwner()
    {
        return $this->belongsTo(ProductServiceOwner::class, 'product_service_owner_id');
    }
    public function orderPackage()
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }
}
