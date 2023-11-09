<?php

namespace Modules\Customer\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\AddressFactory;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';
    protected $fillable = ["id",   "address"	,"treet"	,"city"	,"state",	"postal_code",	"country",	"user_id" , "created_at", "updated_at"];
 
    
    protected static function newFactory(): AddressFactory
    {
        //return AddressFactory::new();
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
