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
use Modules\Order\app\Models\commissionsPackage;
use Modules\Order\app\Models\HistoryPayment;
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
        'phone_number', 'isVerified',  'address', 'date_of_brith', 'cic_number', 'sex', 'adrress', 'date_of_birth', 'city', 'district', 'wards', 'cic_date', 'cic_date_expried', 'phone_number2', 'date_of_birth', 'fcm_token', 'created_byId',
        'note'
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
    public function lastInfo()
    {
        return $this->hasOne(UserInfor::class, 'user_id')->latest();
    }

    public function infor()
    {
        return $this->hasOne(UserInfor::class,'user_id');
    }

    public function salers()
    {
        return $this->hasMany(User::class, 'created_byId')->role('saler');
    }

    public function leaders()
    {
        return $this->hasMany(User::class, 'created_byId');
    }
    public function ownerTeam(){
        return $this->belongsTo(User::class, 'created_byId');
    }
    public function team()
    {
         return $this->belongsTo(User::class, 'created_byId');
    }

    public function saler_orders()
    {

        return $this->hasMany(Order::class, 'sale_id');
    }

    public function sale_order_packages(){
        return $this->hasMany(OrderPackage::class, 'sale_id');
    }


    public function ref_order_packages(){
        return $this->hasMany(OrderPackage::class, 'ref_id');
    }
    public function to_order_packages(){
        return $this->hasMany(OrderPackage::class, 'to_id');
    }
    public function resource_order_packages(){
        return $this->hasMany(OrderPackage::class, 'customer_resources_id');
    }

    public function otps(){
        return $this->hasMany(OtpVerify::class, 'user_id');
    }

    public function scopeSearch($query, array $filters)
    {

        if (isset($filters['search']) && isset($filters['search'])) {

            $query->where('name', 'LIKE', '%' .$filters['search'] . '%');
            $query->orwhere('email', 'LIKE', '%' .$filters['search'] . '%');
            $query->orwhere('phone_number', 'LIKE', '%' .$filters['search'] . '%');
        }

    }

    public function scopeAccept($query, array $filters)
    {

        if (isset($filters['accept']) && isset($filters['accept'])) {

            $query->whereHas('infor',  function($q) use ($filters){
                $q->where('status', $filters['accept']);
            });

        }

    }


    public function historyPayments()
    {
        return $this->hasManyThrough(HistoryPayment::class, OrderPackage::class, 'ref_id','order_package_id');
    }
    public function commission() {
        return $this->hasMany(commissionsPackage::class,'user_id');
    }
}
