<?php

namespace App;

use App\Models\Achievement;
use App\Models\Login;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Role;
use App\Models\Skill;
use App\Models\Voting;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date:Y-m-d',
    ];


    //RELATIONS


    public function joinedCampaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_user_joined')->withTimestamps();
    }

    public function hiddenCampaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_user_hid')->withTimestamps();
    }

    public function votings()
    {
        return $this->belongsToMany(Voting::class)->withTimestamps()->withPivot('variant');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class)->withTimestamps();
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }

    //интересные категории
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class)->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function logins()
    {
        return $this->hasMany(Login::class);
    }



    //METHODS


    //создаваемая кампания
    public function draftedCampaign()
    {
        return $this->hasMany(Campaign::class)->where('readiness', '!=', 'success')->first();
    }

    //вариант за который проголосовал пользователь
    public function votingVariant($voting)
    {
        return $this->votings()->where('id', $voting->id)->first()->pivot->variant;
    }

    //массив ачивментов пользователя
    public function achievmentsArray()
    {
        foreach ($this->achievements as $achievement) {
            $arr[] = $achievement['title'];
        }
        if(isset($arr)){
            return json_encode($arr);
        }
        else{
            return "[]";
        }

    }
}
