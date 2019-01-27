<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{

    public function main()
    {
        $campaigns = Campaign::popular()->where('readiness', 'success')->take(12);
        $campaignsSliderTitle =Block::where('type', 'campaignsSliderTitle')->first();
        $campaignsSliderDesc = Block::where('type', 'campaignsSliderDesc')->first();
        $storiesSliderDesc = Block::where('type', 'storiesSliderDesc')->first();
        $storiesSliderTitle = Block::where('type', 'storiesSliderTitle')->first();

        if (!auth()->check()) {
            $categories = Category::all();
            $test = config('charityTest');
            $about = Block::where('type', 'about')->get();
            $team = Block::where('type', 'team')->get();
            $howItWorks = Block::where('type', 'howItWorks')->first();
            $aboutHeader = Block::where('type', 'aboutHeader')->first();
            $teamHeader = Block::where('type', 'teamHeader')->first();
            return view('main', compact('campaigns', 'test', 'categories', 'about', 'team', 'howItWorks',
                'aboutHeader', 'teamHeader', 'campaignsSliderTitle', 'campaignsSliderDesc', 'storiesSliderTitle', 'storiesSliderDesc'));
        } else {
            $votings = Voting::with('step')->get();
            $votings = $votings->filter(function ($value) { //голосования только в одобренных кампаниях
                return $value->step->campaign->readiness === 'success';
            });
            $votings = $votings->filter(function ($value) { //голосования где шаг активен
                return $value->step->active == 1;
            })->sortByDesc('created_at')->take(12);
            $votesHeader = Block::where('type', 'votesHeader')->first();
            $createCampaign = Block::where('type', 'createCampaign')->first();

            return view('main', compact('campaigns', 'votings', 'campaignsSliderTitle', 'campaignsSliderDesc',
                'storiesSliderTitle', 'storiesSliderDesc', 'votesHeader', 'createCampaign'));
        }
    }

    public function mainGuest()
    {
        if (!auth()->check() || !auth()->user()->can('admin')) {
            return abort(403);
        }

        $campaigns = Campaign::popular()->where('readiness', 'success')->take(12);
        $categories = Category::all();
        $test = config('charityTest');
        $about = Block::where('type', 'about')->get();
        $team = Block::where('type', 'team')->get();
        $howItWorks = Block::where('type', 'howItWorks')->first();
        $aboutHeader = Block::where('type', 'aboutHeader')->first();
        $teamHeader = Block::where('type', 'teamHeader')->first();
        $campaignsSliderTitle =Block::where('type', 'campaignsSliderTitle')->first();
        $campaignsSliderDesc = Block::where('type', 'campaignsSliderDesc')->first();
        $storiesSliderDesc = Block::where('type', 'storiesSliderDesc')->first();
        $storiesSliderTitle = Block::where('type', 'storiesSliderTitle')->first();

        return view('main-guest', compact('campaigns', 'test', 'categories', 'about', 'team', 'howItWorks',
            'aboutHeader', 'teamHeader', 'campaignsSliderTitle', 'campaignsSliderDesc', 'storiesSliderTitle', 'storiesSliderDesc'));
    }

    public function testCharity(Request $request)
    {
        if ($request->answers[2] === 'Phd') {
            $category = 'Science';
        } elseif ($request->answers[2] === 'Bc./Masters' && $request->answers[0] === 'Female') {
            $category = 'Health';
        } elseif ($request->answers[2] === 'Bc./Masters' && $request->answers[0] === 'Male') {
            $category = 'Education';
        } elseif ($request->answers[0] === 'Female') {
            $category = 'Children';
        } else {
            $category = 'Nature';
        }

        //TODO когда будут фильтры формировать ссылку с их использованием
        $link = route('campaigns');

        $result = [
            'link' => $link,
            'category' => $category,
        ];

        return $result;
    }

    public function about()
    {
        $howItWorks = Block::where('type', 'howItWorks')->first();
        $about = Block::where('type', 'about')->get();
        $team = Block::where('type', 'team')->get();
        $aboutHeader = Block::where('type', 'aboutHeader')->first();
        $teamHeader = Block::where('type', 'teamHeader')->first();
        return view('about-us', compact('about', 'team', 'howItWorks', 'aboutHeader', 'teamHeader'));
    }

    public function registerSuccess()
    {
        $block = \App\Models\Block::where('type', 'thankYouRegister')->first();
        return view('thanks-register', compact('block'));
    }


}
