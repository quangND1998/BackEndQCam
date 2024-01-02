<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionSetting extends Model
{
    use HasFactory;
    public function CommissionType(){
        return $this->hasMany(CommissionType::class,'commissionSetting_id');
    }
}
