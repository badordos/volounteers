<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Voting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VotingController extends Controller
{

    public function index(Request $request)
    {
        $votings = Voting::with('step')->get();
        $votings = $votings->filter(function ($value) { //голосования только в одобренных кампаниях
            return $value->step->campaign->readiness === 'success';
        });
        $votings = $votings->filter(function ($value) { //голосования где шаг активен
            return $value->step->active == 1;
        });

        if (auth()->check()) {
            //голосования кампаний к которым пользователь присоединился
            $votings_joined = $votings->filter(function ($value, $key) {
                return $value->step->campaign->joinedUsers->contains(Auth::user());
            });

            //голосования кампаний того же города
            $votings_city = $votings->filter(function ($value, $key) {
                return $value->step->campaign->city_id == Auth::user()->city_id;
            });

            //не вошедшие сортируем по дате
            $votings_date = $votings->diff($votings_joined)->diff($votings_city)->sortBy('created_at');

            $votings = $votings_joined->merge($votings_city)->merge($votings_date);
        } else {
            $votings = $votings->sortBy('created_at');
        }

        return view('vote', compact('votings'));
    }

    //голосование ajax
    public function vote($voting_id, $user_id, Request $request)
    {
        $user = User::find($user_id);
        $voting = Voting::with('step')->find($voting_id);

        if ($voting->step->campaign->joinedUsers->contains($user) && !$voting->users->contains($user)
            && $voting->step->campaign->readiness === 'success') {//если пользователь присоединился к кампании

            $voting->users()->attach($user, ['variant' => $request->selectedVoteVariant]);
            $variants = unserialize($voting->variants);

            foreach($variants as $number => $variant){ //записываем в ответ количество проголосовавших
                $variants[$number]['number_of_votes'] = $voting->users()->where('variant', $number)->count();
            }

            return $variants;
        } else {
            $response['output'] = 'User already voted.';
            return response()->json($response, 403);
        }
    }

    //удалить голосование со страницы кампании
    public function deleteVoting(Campaign $campaign, Request $request)
    {
        if (auth()->user()->can('author', [$campaign])) {
            $voting = Voting::find($request->id);
            $voting->delete();
            return 'success';
        }
        else{
            $response['output'] = 'This user can not delete voting.';
            return response()->json($response, 403);
        }
    }

    //добавить голосование со страницы кампании
    public function addVoting(Campaign $campaign, Request $request)
    {
        if (auth()->user()->can('author', [$campaign])) {
            $voting = Voting::where('step_id', $request->data['id'])->first();
            if (!isset($voting)) {
                $votingNew = new Voting;
                $votingNew->title = $request->data['title'];
                $votingNew->description = $request->data['description'];
                $votingNew->step_id = $request->data['id'];

                $variants = [];
                foreach ($request->data['variants'] as $variant) {
                    $variants[] = ['title' => $variant, 'number_of_votes' => 0];
                }

                $votingNew->variants = serialize($variants);
                $votingNew->save();

                $response['output'] = 'This user can not add voting.';
                return response()->json($response, 200);
            }
            else{
                $response['output'] = 'Voting already exist.';
                return response()->json($response, 200);
            }
        }
        else{
            $response['output'] = 'This user can not add voting.';
            return response()->json($response, 403);
        }
    }
}
