<?php

namespace Modules\Customer\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\ReviewManagementFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Order\app\Models\Order;
use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ReviewManagement extends Model  implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    protected $table = 'review_management';
    /**
     * The attributes that are mass assignable.
     */

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id", "user_id",  "evaluate",  "description",  "order_id",  "state", 'type',    "star",  'data',  "created_at", "updated_at"];

    protected static function newFactory(): ReviewManagementFactory
    {
        //return ReviewManagementFactory::new();
    }

    protected function data(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function review_images()
    {
        return $this->media()->where('collection_name', 'review_images');
    }
}
