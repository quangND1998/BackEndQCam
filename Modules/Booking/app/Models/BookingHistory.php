<?php

namespace Modules\Booking\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Booking\Database\factories\BookingHistoryFactory;
use Illuminate\Support\Carbon;
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
    public function scopeFillter($query, array $filters)
    {
        if (isset($filters['search']) && isset($filters['search'])) {

            $query->where('ballot_code', 'like', '%' . $filters['search'] . '%');
        }
        if (isset($filters['fromdate']) && isset($filters['toDate'])) {

            $query->whereBetween('created_at', [Carbon::parse($filters['fromdate'])->format('Y-m-d H:i:s'), Carbon::parse($filters['toDate'])->format('Y-m-d H:i:s')]);
        }
        if (isset($filters['status'])) {
            if($filters['status'] == "all"){
                $query->get();
            }else{
                $query->where('status', $filters['status']);
            }

        }
    }
}
