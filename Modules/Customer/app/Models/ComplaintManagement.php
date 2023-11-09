<?php

namespace Modules\Customer\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\ComplaintManagementFactory;

class ComplaintManagement extends Model
{
    use HasFactory;
    protected $table = 'complaint_management';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id","user_id",	"description",	"state",	"date",	"created_at","updated_at"];
    
    protected static function newFactory(): ComplaintManagementFactory
    {
        //return ComplaintManagementFactory::new();
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
