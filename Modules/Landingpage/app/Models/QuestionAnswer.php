<?php

namespace Modules\Landingpage\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Landingpage\Database\factories\QuestionAnswerFactory;

class QuestionAnswer extends Model
{
    use HasFactory;
    protected $table = 'question_answers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id","question","answer","type","created_at",'status',	"updated_at"];

    
    protected static function newFactory(): QuestionAnswerFactory
    {
        //return QuestionAnswerFactory::new();
    }



    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['status']) && isset($filters['status'])) {

            $query->where('status', $filters['status']);
        }
        if (isset($filters['type']) && isset($filters['type'])) {

            $query->where('type', $filters['type']);
        }
      
    }
}
