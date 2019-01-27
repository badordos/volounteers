<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    protected $guarded = [];

    //RELATIONS

    public function step()
    {
        return $this->belongsTo(Step::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    //METHODS

    //массив вариантов для frontend'a
    public function variantsArray()
    {
        foreach (unserialize($this->variants) as $variant) {
            $arr[] = str_limit($variant['title'], $limit = 10, $end = '...');
        }
        if(isset($arr)){
            return json_encode($arr);
        }
        else{
            return '';
        }
    }
}
