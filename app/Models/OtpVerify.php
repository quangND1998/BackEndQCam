<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpVerify extends Model
{
    use HasFactory;
    protected $table = 'otp_verifies';
    protected $fillable = [
        'expried_at',
        'otp_number',
        'time_live',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,  'user_id');
    }

}
