<?php

namespace Modules\Customer\app\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role;

class ComplaintManagement extends Model
{
    protected $table = 'complaint_management';
    protected $fillable = [
        "id",
        "user_id",
        "type",
        "description",
        "state",
        "date",
        "created_at",
        "updated_at",
        "severity", // normal - Bình thường, urgent - Xử lý sớm, critical - Nghiêm trọng
        "role_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

}
