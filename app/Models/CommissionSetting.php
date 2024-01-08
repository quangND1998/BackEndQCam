<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class CommissionSetting extends Model
{
    use HasFactory;
    protected $table = 'commission_settings';
    protected $fillable = [
        'id',
        'level_revenue',
        'dateFrom',
        'dateTo',
        'status'
    ];
    public function CommissionType(){
        return $this->hasMany(CommissionType::class,'commissionSetting_id');
    }
    public function commission(){
        return $this->hasMany(Commission::class,'commissionSetting_id');
    }
    public function scopeFillter($query, array $filters)
    {
        if (isset($filters['from']) && isset($filters['to'])) {

            $query->where('dateFrom', Carbon::parse($filters['from'])->format('Y-m-d'))->where('dateTo',Carbon::parse($filters['to'])->format('Y-m-d'));
        }
    }
}
