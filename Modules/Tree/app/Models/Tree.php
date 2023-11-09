<?php

namespace Modules\Tree\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Tree\Database\factories\TreeFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Tree extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    protected $table = 'trees';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id",    "name",    "qr_code",    "land_id", "address", "price",    "state", "status",    "description", "user_manual",    "terms_policy",    "created_at",    "updated_at"];





    protected static function newFactory(): TreeFactory
    {
        //return TreeFactory::new();
    }

    public function land()
    {
        return $this->belongsTo(Land::class, 'land_id');
    }

    public function images()
    {
        return $this->media()->where('collection_name', 'tree_images');
    }
}
