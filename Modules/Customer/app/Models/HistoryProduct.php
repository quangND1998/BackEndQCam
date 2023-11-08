<?php

namespace Modules\Customer\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\HistoryProductFactory;

class HistoryProduct extends Model
{
    use HasFactory;

    protected $table = 'history_products';
    protected $fillable = ["id",   "time_start"	,"time_end","price", "state" ,"product_service_owner_id", "created_at", "updated_at"];
    
    protected static function newFactory(): HistoryProductFactory
    {
        //return HistoryProductFactory::new();
    }

    public function product_service_owner(){
        return $this->belongsTo(ProductServiceOwner::class,'product_service_owner_id');
    }
}
