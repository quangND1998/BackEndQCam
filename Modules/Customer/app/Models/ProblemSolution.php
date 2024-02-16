<?php

namespace Modules\Customer\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Database\factories\ProblemSolutionFactory;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ProblemSolution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'problem_solutions';
    protected $fillable = [];

    protected static function newFactory(): ProblemSolutionFactory
    {
        //return ProblemSolutionFactory::new();
    }
    public function complaint(){
        return $this->belongsTo(ComplaintManagement::class,'complaint_management_id');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
