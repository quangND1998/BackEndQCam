<?php

namespace Modules\Customer\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\CustomerService\app\Models\VisitExtraService;

class ScheduleVisit extends Model
{
    protected $table = 'schedule_visits';

    protected $fillable = [
        'id',
        'date_time',
        'number_adult',
        'number_children',
        'state', // pedning - Đặt lịch, cancel - Hủy lịch, Completed - Đã checkin
        'description',
        'user_id',
        'booking_type', // A - App, CS - Customer Service, V - Vườn
        'product_service_owner_id'
    ];

    public function product_owner_service()
    {
        return $this->belongsTo(ProductServiceOwner::class,'product_service_owner_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function extraServices()
    {
        return $this->belongsToMany(VisitExtraService::class, 'visit_service', 'schedule_visit_id', 'visit_extra_service_id');
    }
}
