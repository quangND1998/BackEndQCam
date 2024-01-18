<?php

namespace Modules\Tree\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Tree\Database\factories\HistoryCareFactory;

class HistoryCare extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'history_cares';
    protected $fillable = ["id", "date","note","trees_id", "created_at", "updated_at"];

    protected static function newFactory(): HistoryCareFactory
    {
        //return HistoryCareFactory::new();
    }
    public function tree()
    {
        return $this->belongsTo(Tree::class, 'trees_id');
    }
    public function activityCare()
    {
        return $this->belongsToMany(ActivityCare::class,'history_cares_activity','history_cares_id','activity_cares_id');
    }
}
