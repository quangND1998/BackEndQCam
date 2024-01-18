<?php

namespace Modules\Booking\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Booking\Database\factories\BookingFactory;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'bookings';
    protected $fillable = ["id", "ballot_number", "ballot_code","user_id","status", "created_at", "updated_at"];

    protected static function newFactory(): BookingFactory
    {
        //return BookingFactory::new();
    }
    // Don vi nhan ma
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function history(){
        return $this->hasMany(BookingHistory::class,'bookings_id');
    }
    public function last_history(){
        return $this->hasOne(BookingHistory::class, 'bookings_id')->orderBy('id','desc')->latest();
    }
}
