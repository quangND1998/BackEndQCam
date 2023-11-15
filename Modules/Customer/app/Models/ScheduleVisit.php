<?php

namespace Modules\Customer\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\ScheduleVisitFactory;

class ScheduleVisit extends Model
{
    use HasFactory;
    protected $table = 'schedule_visits';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['id','date_time','number_adult','number_children','state','product_service_owner_id'];

    protected static function newFactory(): ScheduleVisitFactory
    {
        //return ScheduleVisitFactory::new();
    }
    public function product_owner_service(){
        return $this->belongsTo(ProductServiceOwner::class,'product_service_owner_id');
    }
}
