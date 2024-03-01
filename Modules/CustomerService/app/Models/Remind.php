<?php

namespace Modules\CustomerService\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\app\Models\ProductServiceOwner;

class Remind extends Model
{
    use HasFactory;

    protected $table = 'reminds';
    protected $fillable = [
        "user_id",
        "product_service_owner_id",
        "remind_at",
        "note",
        "call_id"
    ];

    public function csr()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function productServiceOwner()
    {
        return $this->belongsTo(ProductServiceOwner::class, 'product_service_owner_id');
    }
    public function distributeCall()
    {
        return $this->belongsTo(DistributeCall::class,'call_id');
    }
}
