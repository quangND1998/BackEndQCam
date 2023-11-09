<?php

namespace Modules\Customer\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\ProductServiceOwnerFactory;

class ProductServiceOwner extends Model
{
    use HasFactory;

    protected $table = 'product_service_owners';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['id', 'user_id', 'product_service_owner_id', 'time_approve', 'description', 'number_deliveries_current', 'state', 'visited_time'];

    protected static function newFactory(): ProductServiceOwnerFactory
    {
        //return ProductServiceOwnerFactory::new();
    }


    public function product_service_owner()
    {
        return $this->belongsTo(ProductServiceOwner::class, 'product_service_owner_id');
    }
}
