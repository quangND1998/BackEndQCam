<?php

namespace Modules\Order\app\Models;

use App\Models\Commission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class commissionsPackage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];


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

    public function scopeFilterTime($query, array $filters)
    {

        if (isset($filters['date'])) {

            if ($filters['date'] == 'week') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($filters['date'] == 'beforMonth') {
                $query->whereBetween('updated_at', [Carbon::now()->subMonth(1)->startOfMonth(), Carbon::now()->subMonth(1)->endOfMonth()]);
            } elseif ($filters['date'] == 'month') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } elseif ($filters['date'] == 'year') {
                $query->whereBetween('updated_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
            } else {
                $query->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            }
        }

        if (isset($filters['day'])) {

            $query->whereBetween('updated_at', [Carbon::now()->subDay($filters['day']), Carbon::now()]);
        }
        if (isset($filters['from']) && isset($filters['to'])) {

            $query->whereBetween('updated_at', [Carbon::parse($filters['from'])->format('Y-m-d H:i:s'), Carbon::parse($filters['to'])->format('Y-m-d H:i:s')]);
        }
    }
}
