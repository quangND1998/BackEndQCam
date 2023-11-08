<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Modules\Customer\app\Models\Address;
use Modules\Customer\app\Models\ComplaintManagement;
use Modules\Customer\app\Models\ProductServiceOwner;
use Modules\Customer\app\Models\ReviewManagement;
use Modules\Order\app\Models\Order;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number', 'isVerified',  'address', 'date_of_brith', 'cic_number','sex', 'adrress', 'phone_number2', 'date_of_birth'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function getPermissionArray()
    {
        return $this->getAllPermissions()->mapWithKeys(function ($pr) {
            return [$pr['name'] => true];
        });
    }
    public function getRolesArray()
    {
        return $this->roles->mapWithKeys(function ($pr) {
            return [$pr['name'] => true];
        });
    }


    public function orders(){
        return $this->hasMany(Order::class,'user_id');
    }


    public function address(){
        return $this->hasOne(Address::class,'user_id');
    } 


    public function reviews(){
        return $this->hasMany(ReviewManagement::class,'user_id');
    } 

    public function complaints(){
        return $this->hasMany(ComplaintManagement::class,'user_id');
    } 


    public function product_service_owners(){

        return $this->belongsToMany(ProductServiceOwner::class, 'product_service_owners', 'user_id', 'product_service_id');
    
    }
}
