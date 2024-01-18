<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;
    protected $table = 'commissions';
    protected $fillable = [
        'name',
        'spend_from',
        'spend_to',
        'commission',
        'type',
        'status',
        'greater',
        'level_revenue',
        'discount_form_sale',
        'discount_form_manager_sale',
        'user_type_id',
        'commission_type_id',
        'commissionSetting_id'
    ];
    public function type(){
        return $this->belongsTo(CommissionType::class,'commission_type_id');
    }
    public function user(){
        return $this->belongsTo(CommissionType::class,'commission_type_id');
    }
}

