<?php

namespace Modules\Booking\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Booking\Database\factories\BookingHistoryFactory;

class BookingHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'booking_histories';
    protected $fillable = ["id", "ballot_code", "dateStart","dateEnd","status","reason","ref_id","bookings_id", "created_at", "updated_at"];

    protected static function newFactory(): BookingHistoryFactory
    {
        //return BookingHistoryFactory::new();
    }
    public function ref(){
        return $this->belongsTo(User::class, 'ref_id');
    }
    public function booking(){
        return $this->belongsTo(Booking::class, 'bookings_id');
    }
}
