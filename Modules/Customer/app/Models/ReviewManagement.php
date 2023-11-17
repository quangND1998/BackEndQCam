<?php

namespace Modules\Customer\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\ReviewManagementFactory;

class ReviewManagement extends Model
{
    use HasFactory;
    protected $table = 'complaint_management';
    /**
     * The attributes that are mass assignable.
     */

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id", "user_id",  "evaluate",  "description",  "order_id",  "state", 'type',    "star",    "created_at", "updated_at"];

    protected static function newFactory(): ReviewManagementFactory
    {
        //return ReviewManagementFactory::new();
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
