<?php

namespace Modules\CallCenter\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CallCenter\Database\factories\HistoryCallFactory;

class HistoryCall extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): HistoryCallFactory
    {
        //return HistoryCallFactory::new();
    }
}
