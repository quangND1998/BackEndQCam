<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;
    protected $table = 'commissions';
    protected $fillable = [
        'name',
        'spend_from',
        'spend_to',
        'commission',
        'type',
        'status',
        'greater',
        'level_revenue'
    ];
}

