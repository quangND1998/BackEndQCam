<?php

namespace Modules\Customer\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\HistoryContractFactory;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class HistoryContract extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "history_contracts";
    protected $fillable = [];

    protected static function newFactory(): HistoryContractFactory
    {
        //return HistoryContractFactory::new();
    }
    public function images()
    {
        return $this->media()->where('collection_name', 'contract_images');
    }
    public function contracts(){
        return $this->belongsTo(contracts::class,'contract_id');
    }
    
}
