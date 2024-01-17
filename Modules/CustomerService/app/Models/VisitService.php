<?php

namespace Modules\CustomerService\app\Models;

use Illuminate\Database\Eloquent\Model;

class VisitService extends Model
{
    protected $table = 'visit_service';

    protected $fillable = [
        'schedule_visit_id',
        'visit_extra_service_id'
    ];
}
