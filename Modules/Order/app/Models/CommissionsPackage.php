<?php

namespace Modules\Order\app\Models;

use App\Models\Commission;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Database\factories\CommissionsPackageFactory;

class commissionsPackage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): CommissionsPackageFactory
    {
        //return CommissionsPackageFactory::new();
    }
    public function orderpackage()
    {
        return $this->belongsTo(OrderPackage::class,'order_package_id');
    }
    // chinh sach ban hang
    public function commissions()
    {
        return $this->belongsTo(Commission::class,'commissions_id');
    }
    // lịch su thanh toan hoa hồng
    public function commissionsHistory()
    {
        return $this->hasMany(commissionsHistory::class,'commissions_packages_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
