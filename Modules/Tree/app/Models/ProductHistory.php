<?php

namespace Modules\Tree\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Tree\Database\factories\ProductHistoryFactory;

class ProductHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = "product_histories";
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["id", "unit", "expected_quantity", 'actual_quantity', "date_add", 'date_expire', "state", 'state_confirm', 'product_retail_id'];

    protected static function newFactory(): ProductHistoryFactory
    {
        //return ProductHistoryFactory::new();
    }
    public function product_retail(){
        return $this->belongsTo(ProductRetail::class,'product_retail_id');
    }
}
