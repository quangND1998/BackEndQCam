<?php

namespace Modules\Customer\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\ProductServiceOwnerFactory;
use Modules\Tree\app\Models\ProductService;
use Modules\Tree\app\Models\Tree;
use Modules\Customer\app\Models\Contract;
class ProductServiceOwner extends Model
{
    use HasFactory;

    protected $table = 'product_service_owners';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['id', 'user_id', 'product_service_owner_id', 'time_approve','time_end', 'description', 'number_deliveries_current', 'state', 'visited_time'];

    protected static function newFactory(): ProductServiceOwnerFactory
    {
        //return ProductServiceOwnerFactory::new();
    }


    public function product()
    {
        return $this->belongsTo(ProductService::class, 'product_service_id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function history_product()
    {
        return $this->hasMany(HistoryProduct::class,'product_service_owner_id');
    }
    public function history_use_service()
    {
        return $this->hasMany(HistoryUseService::class,'product_service_owner_id');
    }
    public function history_extend()
    {
        return $this->hasMany(HistoryExtend::class,'product_service_owner_id');
    }
    public function trees()
    {
        return $this->hasMany(Tree::class,'product_service_owner_id');
    }
    public function contract()
    {
        return $this->hasOne(Contract::class,'product_service_owner_id');
    }
    public function visit(){
        return $this->hasMany(ScheduleVisit::class,'product_service_owner_id');
    }
}
