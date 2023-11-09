<?php

namespace Modules\Landingpage\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Landingpage\Database\factories\NewsFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class News extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $table = 'news';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id","idPriority","link","type","title","short_description","description","state","created_at",	"updated_at"];

    protected static function newFactory(): NewsFactory
    {
        //return NewsFactory::new();
    }
    public function images()
    {
        return $this->media()->where('collection_name', 'news_images');
    }

}
