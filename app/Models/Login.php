<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    public $guarded = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
