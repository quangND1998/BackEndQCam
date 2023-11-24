<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'personal_team'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class,  'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(Team::class, User::class, 'team_user', 'team_id', 'user_id');
    }


    public function allUsers()
    {
        return $this->users->merge([$this->owner]);
    }

  
}
