<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    //все админы
    public static function admins(){
        return Role::with('users')->where('title', 'admin')->first()->users;
    }
}
