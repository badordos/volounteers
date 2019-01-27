<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Campaign;
use App\Models\City;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $city = City::find($user->city_id);
        $age = Carbon::parse($user->date_of_birth)->age;
        $skills = $user->skills;
        $categories = $user->categories;
        $achievements = $user->achievements;

        $campaigns = Campaign::with('joinedUsers')->get();
        $campaigns = $campaigns->filter(function ($value, $key) {
            return $value->joinedUsers->contains(auth()->user());
        });

        return view('about-me', compact('user', 'campaigns', 'city', 'age', 'skills', 'categories', 'achievements'));
    }

    public function campaigns()
    {
        $user = auth()->user();
        $createdCampaigns = Campaign::where('user_id', $user->id)->with('steps')->get()->sortByDesc('created_at');

        $chosenCategories = $user->categories;
        foreach ($chosenCategories as $category) {
            $arr[] = $category->id;
        }

        //кампании созданные за последнюю неделю
        $newCampaigns = Campaign::where('readiness', 'success')->where('created_at', '>=', Carbon::now()->subWeek())->with('steps')->get();

        //если есть интересующие категории берем подходящие кампании
        if (!empty($arr)) {
            $intrestedCampaigns = Campaign::where('readiness', 'success')->with('steps')->get()->whereIn('category_id', $arr);
            $CampaignsToTakePart = $newCampaigns->merge($intrestedCampaigns);
        } else {//иначе берем мировые
            $worldwideCampaigns = Campaign::where('readiness', 'success')->where('worldwide', true)->with('steps')->get();
            $CampaignsToTakePart = $newCampaigns->merge($worldwideCampaigns);
        }

        $CampaignsToTakePart->sortByDesc('created_at');

        return view('my-campaigns', compact('createdCampaigns', 'CampaignsToTakePart'));
    }

    public function achievements()
    {
        $achievements = Achievement::all();

        return view('achievements', compact('achievements'));
    }

    public function profile(User $user)
    {
        //если переходим в собственный профиль - редирект на ЛК
        if (auth()->user() !== null && auth()->user()->id === $user->id) {
            return redirect(route('profile.about-me'));
        }

        $city = City::find($user->city_id);
        $age = Carbon::parse($user->date_of_birth)->age;
        $skills = $user->skills;
        $categories = $user->categories;
        $achievements = $user->achievements;

        $campaigns = Campaign::where(['user_id' => $user->id, 'readiness' => 'success'])->get()->sortByDesc('created_at');

        return view('profile', compact('user', 'city', 'age', 'skills', 'categories', 'achievements', 'campaigns'));
    }

}
