<?php

namespace Modules\Customer\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\ComplaintManagementFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Order\app\Models\OrderPackage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ComplaintManagement extends Model
{
    protected $table = 'complaint_management';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id", "user_id", 'type',    "description",  "severity",  "state",
    "date", "data", "star", "role_id",  "created_at", "updated_at",
    "product_service_owner_id",'resource','code','counselor_staff_id','status'];

    protected static function newFactory(): ComplaintManagementFactory
    {
        //return ComplaintManagementFactory::new();
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

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function product_service_owner()
    {
        return $this->belongsTo(ProductServiceOwner::class, 'product_service_owner_id');
    }
    public function counselor_staff()
    {
        return $this->belongsTo(User::class,'counselor_staff_id');
    }
    public function problem_solution()
    {
        return $this->hasMany(ProblemSolution::class,'complaint_management_id');
    }
}
