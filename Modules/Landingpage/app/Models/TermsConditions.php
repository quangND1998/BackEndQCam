<?php

namespace Modules\Landingpage\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Landingpage\Database\factories\TermsConditionsFactory;

class TermsConditions extends Model
{
    use HasFactory;
    protected $table = 'terms_conditions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id","title","sub_title","description","created_at",	"updated_at"];
  
    
    protected static function newFactory(): TermsConditionsFactory
    {
        //return TermsConditionsFactory::new();
    }
}
