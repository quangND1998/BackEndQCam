<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone_number', 'address', 'date_of_brith', 'cic_number', 'sex', 'adrress', 'date_of_birth', 'city', 'district', 'wards', 'cic_date', 'cic_date_expried', 'status', 'photo_url', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
