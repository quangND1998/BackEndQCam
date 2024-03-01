<?php

namespace Modules\Tree\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Tree\Database\factories\ProductFailFactory;

class ProductFail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "product_fails";
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id", "id_priority", "name",'quality','reason', 'code','unit','user_add'];

    public function user(){
        return $this->belongsTo(User::class, 'user_add');
    }
    public function product_retail(){
        return $this->belongsTo(ProductRetail::class, 'product_retail_id');
    }
}
