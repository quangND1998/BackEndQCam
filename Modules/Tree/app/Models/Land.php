<?php

namespace Modules\Tree\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Tree\Database\factories\LandFactory;

class Land extends Model 
{
    use HasFactory;

    protected $table = 'lands';
    protected $fillable = ["id",   "name", "state", "created_at", "updated_at"];

    protected static function newFactory(): LandFactory
    {
        //return LandFactory::new();
    }

    public function trees()
    {
        return $this->hasMany(Tree::class, 'land_id');
    }
}
