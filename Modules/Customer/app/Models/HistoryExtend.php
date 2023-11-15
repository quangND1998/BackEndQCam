<?php

namespace Modules\Customer\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\HistoryExtendFactory;

class HistoryExtend extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): HistoryExtendFactory
    {
        //return HistoryExtendFactory::new();
    }
    public function contract()
    {
        return $this->hasOne(Contract::class,'extend_id');
    }
    public function product_service_owner(){
        return $this->belongsTo(ProductServiceOwner::class,'product_service_owner_id');
    }
}
