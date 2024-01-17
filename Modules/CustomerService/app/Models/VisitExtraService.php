<?php

namespace Modules\CustomerService\app\Models;

use Illuminate\Database\Eloquent\Model;

class VisitExtraService extends Model
{
    protected $table = 'visit_extra_services';

    protected $fillable = [
        'name',
        'is_active'
    ];
}
