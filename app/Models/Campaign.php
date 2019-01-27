<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Campaign extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = ['id'];

    protected $with = ['user'];

    //RELATIONS

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function joinedUsers()
    {
        return $this->belongsToMany(User::class, 'campaign_user_joined')->withTimestamps();
    }

    public function hidUsers()
    {
        return $this->belongsToMany(User::class, 'campaign_user_hid')->withTimestamps();
    }


    //METHODS

    //популярные: наибольшее количество присоединившихся пользователей
    public static function popular()
    {
        return Campaign::with('joinedUsers')->get()->sortByDesc(function ($campaign) {
            return $campaign->joinedUsers->count();
        });
    }

    //считает активный шаг кампании
    public function numberOfActiveStep(Collection $steps)
    {
        $i = 1;
        foreach ($steps as $step) {
            if ($step->active == true) {
                return $i;
            }
            $i++;
        }
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('images');
        $this->addMediaCollection('main_images');
        $this->addMediaCollection('preview_image');
        $this->addMediaCollection('main_video_preview');
        $this->addMediaCollection('main_videos');

    }

    //Если пользователь не присоединился к кампании и шаг активный - выводим попап join
    public function joinPopup(Step $step)
    {
        return !$this->joinedUsers->contains(auth()->user()) && $step->active == true;
    }

    public function registerMediaConversions(Media $media = null)
    {
        if($media->collection_name === 'preview_image' && $media->mime_type !== 'video/mp4'){
            $this->addMediaConversion('thumb')
                ->width(368)
                ->height(232)
                ->sharpen(10);

            $this->addMediaConversion('small')
                ->width(736)
                ->height(464);
        }
    }

}
