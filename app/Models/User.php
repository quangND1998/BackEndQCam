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
use Modules\Order\app\Models\OrderPackage;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use InteractsWithMedia;
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
        'username',
        'email',
        'password',
        'phone_number', 'isVerified',  'address', 'date_of_brith', 'cic_number', 'sex', 'adrress', 'date_of_birth', 'city', 'district', 'wards', 'cic_date', 'cic_date_expried', 'phone_number2', 'date_of_birth', 'fcm_token', 'created_byId'
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


    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    public function orderPackage()
    {
        return $this->hasMany(OrderPackage::class, 'user_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'user_id');
    }


    public function reviews()
    {
        return $this->hasMany(ReviewManagement::class, 'user_id');
    }

    public function complaints()
    {
        return $this->hasMany(ComplaintManagement::class, 'user_id');
    }


    public function product_service_owners()
    {

        return $this->hasMany(ProductServiceOwner::class, 'user_id');
    }


    public function related_images()
    {
        return $this->media()->where('collection_name', 'related_images');
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }


    public function shipper_orders()
    {

        return $this->hasMany(Order::class, 'shipper_id');
    }

    public function teams()
    {
        return $this->belongsToMany(User::class, Teams::class, 'team_user', 'user_id', 'team_id');
    }

    public function infors()
    {
        return $this->hasMany(UserInfor::class, 'user_id');
    }

    public function lastInfoChange()
    {
        return $this->hasOne(UserInfor::class)->latest();
    }

    public function salers()
    {
        return $this->hasMany(User::class, 'created_byId')->role('saler');
    }

    public function leaders()
    {
        return $this->hasMany(User::class, 'created_byId')->role('leader-sale');
    }

    public function team()
    {
        return $this->belongsTo(User::class, 'created_byId')->role('leader-sale');
    }
}
