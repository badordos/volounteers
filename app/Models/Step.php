<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Step extends Model
{
    protected $guarded = [];

    public $with = [
        'campaign',
        'voting',
    ];

    //RELATIONS

    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }

    public function voting(){
        return $this->hasOne(Voting::class);
    }

    //METHODS

    //проверяет, находится ли текущий шаг ниже активного. Если находится - возвращает true
    public function lowerStep(Collection $steps, $currentStep){
        foreach($steps as $step){
            if($step->active == false && $step->id == $currentStep->id){
                return true;
            }
            if($step->active == true){
                return false;
            }
        }
    }

}
