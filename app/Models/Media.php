<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $table = 'media';


    
    public function scopeFillter($query, array $filters)
    {

        if (isset($filters['minetype'])) {

            $query->where('mime_type',$filters['mintype']);
        }
        if (isset($filters['fromDate']) && isset($filters['toDate'])) {

            $query->whereBetween('created_at', [Carbon::parse($filters['fromDate'])->format('Y-m-d H:i:s'), Carbon::parse($filters['toDate'])->format('Y-m-d H:i:s')]);
        }

        if (isset($filters['payment_status'])) {

            $query->where('payment_status', $filters['payment_status']);
        }


        if (isset($filters['payment_method'])) {

            $query->where('payment_method', $filters['payment_method']);
        }
        if (isset($filters['type'])) {

            $query->where('type', $filters['type']);
        }
    }

}
