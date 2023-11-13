<?php

namespace Modules\Landingpage\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Landingpage\Database\factories\ContactFactory;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id","email","name","address","tax_code","hotline","zalo_phone","facebook","website","map","created_at",	"updated_at"];
    
    protected static function newFactory(): ContactFactory
    {
        //return ContactFactory::new();
    }
}
