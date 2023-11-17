<?php

namespace Modules\Customer\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\ContractFactory;

class Contract extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $table = "contracts";
    /**
     * The attributes that are mass assignable.
     */

    protected static function newFactory(): ContactFactory
    {
        //return ContactFactory::new();
    }

    public function product_owner_service(){
        return $this->belongsTo(ProductServiceOwner::class,'product_service_owner_id');
    }
    public function history_contact(){
        return $this->hasMany(HistoryContract::class,'contracts_id');
    }
    public function lastcontract(){
        return $this->hasOne(HistoryContract::class,'contracts_id')->latest();
    }

}
