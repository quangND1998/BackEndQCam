<?php

namespace Modules\Tree\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Tree\Database\factories\ActivityCareFactory;

class ActivityCare extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'activity_cares';
    protected $fillable = ["id",   "name", "created_at", "updated_at"];

    protected static function newFactory(): ActivityCareFactory
    {
        //return ActivityCareFactory::new();
    }
}
