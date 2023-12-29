<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionType extends Model
{
    use HasFactory;
    public function participants(){
        return $this->belongsToMany(UserType::class,'commission_usertypes','commission_type_id','user_type_id');
    }
}
