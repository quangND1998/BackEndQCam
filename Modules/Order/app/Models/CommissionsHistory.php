<?php

namespace Modules\Order\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\CommissionsHistoryFactory;

class commissionsHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): CommissionsHistoryFactory
    {
        //return CommissionsHistoryFactory::new();
    }
    public function commissionPackage(){
        return $this->belongsTo(commissionsPackage::class,'commissions_packages_id');
    }
}
