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
    protected $fillable = ["id","question","answer","type","created_at",	"updated_at"];

    
    protected static function newFactory(): QuestionAnswerFactory
    {
        //return QuestionAnswerFactory::new();
    }
}
