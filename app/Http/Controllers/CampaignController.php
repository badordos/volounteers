<?php

namespace App\Http\Controllers;

use App\Events\CampaignCreationCompleted;
use App\Events\UserHideCampaign;
use App\Http\Requests\StoreStepFive;
use App\Http\Requests\StoreStepFour;
use App\Http\Requests\StoreStepOne;
use App\Http\Requests\StoreStepThree;
use App\Http\Requests\StoreStepTwo;
use App\Models\Block;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\City;
use App\Models\Step;
use App\Models\Voting;
use App\User;
use Illuminate\Http\Request;
use Pbmedia\LaravelFFMpeg\FFMpegFacade as FFMpeg;

class CampaignController extends Controller
{
    protected $draftedCampaign;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (auth()->check()) {
                $this->draftedCampaign = auth()->user()->draftedCampaign();
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $campaigns = Campaign::where('readiness', 'success')->with('joinedUsers')->latest()->get();

        //если запрос на поздагрузку
        if (isset($request->count)) {
            $campaigns = $campaigns->slice($request->count + 1)->take(12);
            foreach ($campaigns as $campaign) {
                $result['campaigns'][] = $campaign;
            }
            $result['isEmpty'] = $campaigns->count() < 12 ? $result['isEmpty'] = true : $result['isEmpty'] = false;
            return response()->json($result);
        }

        return view('campaigns', compact('campaigns'));
    }

    public function show(Campaign $campaign)
    {
        //Если кампания не готова и пользователь неавторизован - возвращаем в список кампаний
        if ($campaign->readiness !== 'success' && auth()->user() == null) {
            return redirect(route('campaigns'));
        }//или пользователь не автор и пользователь не админ
        elseif ($campaign->readiness !== 'success' && auth()->user()->can('creator') == false && auth()->user()->can('admin') == false) {
            return redirect(route('campaigns'));
        }

        $steps = $campaign->steps()->get();

        $media_main = $campaign->getFirstMedia('main_images');
        $media = $campaign->getMedia('images');
        $video = $campaign->getFirstMedia('main_videos');
        $video_preview = $campaign->getFirstMedia('main_video_preview');

        $volunteers = $campaign->joinedUsers()->count();
        $percent = $campaign->volunteers_needed != 0 ? round($volunteers / $campaign->volunteers_needed * 100, 2) : 100;

        return view('single-campaign', compact('campaign', 'steps', 'media', 'media_main', 'video', 'volunteers', 'percent', 'video_preview'));
    }


    public function createStepOne($edit = null)
    {
        if (isset($this->draftedCampaign) && $this->draftedCampaign->readiness == 'moderation') {
            return view('moderation');
        }
        $categories = Category::all()->toJson();
        $cities = City::all()->toJson();

        //если кампания уже существует и ее создание не завершено редирект на нужный шаг
        if (isset($this->draftedCampaign) && $edit !== 'edit') {
            switch ($this->draftedCampaign->readiness) {
                case '1':
                    return view('create-campaign-step-1', ['campaign' => $this->draftedCampaign, 'cities' => $cities, 'categories' => $categories]);
                    break;
                case '2':
                    return redirect(route('create-campaign-step-2'));
                    break;
                case '3':
                    return redirect(route('create-campaign-step-3'));
                    break;
                case '4':
                    return redirect(route('create-campaign-step-4'));
                    break;
                case '5':
                    return redirect(route('create-campaign-step-5'));
                    break;
            }
        }

        return view('create-campaign-step-1', ['campaign' => $this->draftedCampaign, 'cities' => $cities, 'categories' => $categories]);
    }


    public function storeStepOne(StoreStepOne $request)
    {
        //если кампания новая создаем
        if ($this->draftedCampaign === null) {
            $campaign = new Campaign;
            $campaign->title = $request->title;
            $campaign->description = $request->description;
            $campaign->volunteers_needed = $request->volunteers_needed;
            $campaign->user_id = auth()->user()->id;
            $campaign->readiness = '2';
            $campaign->category_id = $request->category_id;
            //если кампания мировая
            if (isset($request->worldwide) && $request->worldwide === 'on') {
                $campaign->worldwide = true;
            } else {//иначе записываем город
                $campaign->city_id = $request->city_id;
            }
            $campaign->save();
            $campaign->joinedUsers()->attach(auth()->user()); //Сразу присоединяем автора к кампании
        } else { //иначе обновляем
            $this->draftedCampaign->title = $request->title;
            $this->draftedCampaign->description = $request->description;
            $this->draftedCampaign->volunteers_needed = $request->volunteers_needed;
            $this->draftedCampaign->category_id = $request->category_id;
            //если кампания мировая
            if (isset($request->worldwide) && $request->worldwide === 'on') {
                $this->draftedCampaign->worldwide = true;
                $this->draftedCampaign->city_id = null;
            } else {//иначе записываем город
                $this->draftedCampaign->city_id = $request->city_id;
                $this->draftedCampaign->worldwide = false;
            }
            $this->draftedCampaign->update();
        }

        return redirect(route('create-campaign-step-2'));
    }

    public function createStepTwo()
    {
        if ($this->draftedCampaign === null) {
            return redirect(route('create-campaign-step-1'));
        }
        if ($this->draftedCampaign->readiness == 'moderation') {
            return view('moderation');
        }

        $media_main = $this->draftedCampaign->getMedia('main_images');
        $media = $this->draftedCampaign->getMedia('images');
        $video = $this->draftedCampaign->getMedia('main_videos');
        $video_preview = $this->draftedCampaign->getMedia('main_video_preview');


        if ($media_main->isNotEmpty() || $media->isNotEmpty() || $video->isNotEmpty()) {
            return view('create-campaign-step-2', ['campaign' => $this->draftedCampaign, 'media_main' => $media_main,
                'media' => $media, 'video' => $video, 'video_preview' => $video_preview]);
        }
        return view('create-campaign-step-2', ['campaign' => $this->draftedCampaign]);
    }

    public function uploadStepTwo(StoreStepTwo $request)
    {
        if (isset($request->main_image)) {
            $this->draftedCampaign->clearMediaCollection('main_images');

            $this->draftedCampaign->addMediaFromRequest('main_image')
                ->usingFileName($request->main_image->hashName())
                ->preservingOriginal()
                ->toMediaCollection('main_images');

            if ($this->draftedCampaign->getMedia('main_videos')->isNotEmpty()) {
                $this->draftedCampaign->clearMediaCollection('main_videos');
            }
        }

        if (isset($request->main_video_preview)) {

            $this->draftedCampaign->clearMediaCollection('main_video_preview');

            $this->draftedCampaign->addMediaFromRequest('main_video_preview')
                ->usingFileName($request->main_video_preview->hashName())
                ->preservingOriginal()
                ->toMediaCollection('main_video_preview');
        }

        foreach ($request->files as $file) {
            foreach ($file as $image) {
                if ($image !== null) {
                    $this->draftedCampaign->addMedia($image)
                        ->usingFileName(md5($image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension())
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                }
            }
        }

        if ($this->draftedCampaign->readiness <= '2') {
            $this->draftedCampaign->readiness = '3';
            $this->draftedCampaign->update();
        }
        if ($request->next_step_2 == 'true') {
            return redirect(route('create-campaign-step-3'));
        } elseif ($request->back_step_2 == 'true') {
            return redirect(route('create-campaign-step-1', ['edit' => 'edit']));
        }
    }

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'file' => 'mimes:mp4,webm, | max:50000',
        ]);
        if (isset($request->file)) {
            try {
                $this->draftedCampaign->clearMediaCollection('main_videos');
                $this->draftedCampaign->clearMediaCollection('main_video_preview');

                $this->draftedCampaign->addMediaFromRequest('file')
                    ->preservingOriginal()
                    ->toMediaCollection('main_videos');

                $video = $this->draftedCampaign->getFirstMedia('main_videos');

                $videoName = str_slug($video->file_name);

                FFMpeg::fromDisk('public')
                    ->open($video->id . '/' . $video->file_name)
                    ->getFrameFromSeconds(2)
                    ->export()
                    ->toDisk('public')
                    ->save( $videoName . '.png');

                $this->draftedCampaign->addMedia(public_path() . '/storage/' . $videoName . '.png')
                    ->preservingOriginal()
                    ->toMediaCollection('main_video_preview');

                if ($this->draftedCampaign->getMedia('main_images')->isNotEmpty()) {
                    $this->draftedCampaign->clearMediaCollection('main_images');
                }

                $response['output'] = 'Upload successful.';
                $response['preview'] = '/storage/' . $videoName . '.png';

                return response()->json($response, 200);
            } catch (\Exception $e) {
                logger($e);
                $response['output'] = 'Some error. Try later.';
                return response()->json($response, 500);
            }
        }
    }

    public function deleteStepTwo($type = null, Request $request)
    {
        if ($type !== null) {
            $this->draftedCampaign->clearMediaCollection($type);
            if($type == 'main_videos'){
                $this->draftedCampaign->clearMediaCollection('main_video_preview');
            }
            $response['output'] = 'Delete successful.';
            return response()->json($response, 200);
        }

        if (isset($request->id)) {
            $this->draftedCampaign->deleteMedia($request->id);
            $response['output'] = 'Delete successful.';
            return response()->json($response, 200);
        } else {
            $response['output'] = 'Some error. Try later.';
            return response()->json($response, 500);
        }
    }

    public function createStepThree()
    {
        if ($this->draftedCampaign === null) {
            return redirect(route('create-campaign-step-1'));
        }
        if (isset($this->draftedCampaign) && $this->draftedCampaign->readiness == 'moderation') {
            return view('moderation');
        }

        //формируем корректный json для вывода старых значений
        foreach ($this->draftedCampaign->steps as $step) {
            $currentStep = [];
            $currentStep['id'] = $step->id;
            $currentStep['title'] = $step->title;
            $currentStep['description'] = $step->description;
            if (isset($step->voting)) {
                $currentStep['vote']['title'] = $step->voting->title;
                $currentStep['vote']['description'] = $step->voting->description;
                $currentStep['vote']['variants'] = array();
                foreach (unserialize($step->voting->variants) as $variant) {
                    $currentStep['vote']['variants'][] = $variant['title'];
                }
            }
            $oldData[] = $currentStep;
        }

        if (isset($oldData)) {
            $oldData = json_encode($oldData);
            return view('create-campaign-step-3', ['campaign' => $this->draftedCampaign, 'oldData' => $oldData]);
        } else {
            return view('create-campaign-step-3', ['campaign' => $this->draftedCampaign]);
        }
    }

    public function storeStepThree(Request $request)
    {
        if ($request->back_step_3 == 'true' && empty(array_filter($request->steps['step1']))) {
            return redirect(route('create-campaign-step-2'));
        }
        $request->validate([
            'steps.*.title' => 'string|min:3|max:120',
            'steps.*.description' => 'string|min:3|max:1000',
            'steps.*.vote.title' => 'string|min:3|max:120',
            'steps.*.vote.description' => 'string|min:3|max:1000',
            'steps.*.vote.variants.*' => 'string|min:1|max:60',
        ]);

        //удаляем старые, если есть
        foreach ($this->draftedCampaign->steps as $oldStep) {
            $oldStep->delete();
        }

        $i = 0;
        foreach ($request->steps as $step) {

            if ($i === 0) { //первый шаг делать активным
                $stepNew = Step::create([
                    'title' => $step['title'],
                    'description' => $step['description'],
                    'campaign_id' => $this->draftedCampaign->id,
                    'active' => true,
                ]);
                $i++;
            } else {
                $stepNew = Step::create([
                    'title' => $step['title'],
                    'description' => $step['description'],
                    'campaign_id' => $this->draftedCampaign->id,
                ]);
            }


            if (isset($step['vote'])) {
                $votingNew = new Voting;
                $votingNew->title = $step['vote']['title'];
                $votingNew->description = $step['vote']['description'];
                $votingNew->step_id = $stepNew->id;

                $variants = [];
                foreach ($step['vote']['variants'] as $variant) {
                    $variants[] = ['title' => $variant];
                }

                $votingNew->variants = serialize($variants);
                $votingNew->save();
            }
        }

        if ($this->draftedCampaign->readiness <= '3') {
            $this->draftedCampaign->readiness = '4';
            $this->draftedCampaign->update();
        }

        if ($request->next_step_3 == 'true') {
            return redirect(route('create-campaign-step-4'));
        } elseif ($request->back_step_3 == 'true') {
            return redirect(route('create-campaign-step-2'));
        }

    }

    public function createStepFour()
    {
        if ($this->draftedCampaign === null) {
            return redirect(route('create-campaign-step-1'));
        }
        if (isset($this->draftedCampaign) && $this->draftedCampaign->readiness == 'moderation') {
            return view('moderation');
        }
        return view('create-campaign-step-4', ['campaign' => $this->draftedCampaign]);
    }

    public function storeStepFour(Request $request)
    {
        if ($request->back_step_4 == 'true' && $request->about_desc == null) {
            return redirect(route('create-campaign-step-3'));
        }
        $request->validate([
            'about_desc' => 'required | string | max: 20000000',
        ]);

        $this->draftedCampaign->about_desc = $request->about_desc;
        $this->draftedCampaign->update();

        if ($this->draftedCampaign->readiness <= '4') {
            $this->draftedCampaign->readiness = '5';
            $this->draftedCampaign->update();
        }

        if ($request->next_step_4 == 'true') {
            return redirect(route('create-campaign-step-5'));
        } elseif ($request->back_step_4 == 'true') {
            return redirect(route('create-campaign-step-3'));
        }
    }

    public function createStepFive()
    {
        if ($this->draftedCampaign === null) {
            return redirect(route('create-campaign-step-1'));
        }
        if (isset($this->draftedCampaign) && $this->draftedCampaign->readiness == 'moderation') {
            return view('moderation');
        }
        $image = $this->draftedCampaign->getMedia('preview_image')->first();
        return view('create-campaign-step-5', ['campaign' => $this->draftedCampaign, 'image' => $image]);
    }

    public function storeStepFive(StoreStepFive $request)
    {
        if (isset($request->preview_image)) {
            $this->draftedCampaign->clearMediaCollection('preview_image');
            $this->draftedCampaign->addMediaFromRequest('preview_image')
                ->usingFileName($request->preview_image->hashName())
                ->preservingOriginal()
                ->toMediaCollection('preview_image');
        }

        if ($request->back_step_5 == 'true') {
            return redirect(route('create-campaign-step-4'));
        } elseif ($request->creation_complete == 'true') {
            return redirect(route('creationComplete'));
        }
    }

    public function preview()
    {
        if ($this->draftedCampaign === null) {
            return redirect(route('create-campaign-step-1'));
        }
        $creator = $this->draftedCampaign->user;
        $steps = $this->draftedCampaign->steps()->get();

        $media_main = $this->draftedCampaign->getFirstMedia('main_images');
        $media = $this->draftedCampaign->getMedia('images');
        $video = $this->draftedCampaign->getFirstMedia('main_videos');
        $video_preview = $this->draftedCampaign->getFirstMedia('main_video_preview');

        $volunteers = $this->draftedCampaign->joinedUsers()->count();
        $percent = $this->draftedCampaign->volunteers_needed != 0 ? round($volunteers / $this->draftedCampaign->volunteers_needed * 100, 2) : 100;

        $preview = true;

        return view('single-campaign', ['campaign' => $this->draftedCampaign, 'steps' => $steps, 'media' => $media,
            'media_main' => $media_main, 'video' => $video, 'volunteers' => $volunteers, 'percent' => $percent, 'creator' => $creator, 'preview' => $preview,
            'video_preview' => $video_preview]);
    }

    public function creationComplete()
    {
        if ($this->draftedCampaign === null) {
            return redirect(route('create-campaign-step-1'));
        }
        //отправляем админу уведомление о новой кампании
        event(new CampaignCreationCompleted($this->draftedCampaign, auth()->user()));

        $this->draftedCampaign->readiness = 'moderation';
        $this->draftedCampaign->update();

        $block = Block::where('type', 'thankYou')->first();

        return view('thank-you', ['campaign' => $this->draftedCampaign, 'block' => $block]);
    }

    //присоединиться к кампании
    public function join(Campaign $campaign, User $user = null)
    {
        if ($user !== null) {
            $campaign->joinedUsers()->attach($user->id);
            return back();
        }
        return redirect(route('login'));
    }

    //скрыть кампанию
    public function hide(Campaign $campaign, User $user = null, Request $request)
    {
        //отправляем админу уведомление о жалобе на кампанию
        $text_reason = $request->text_reason;
        event(new UserHideCampaign($campaign, $text_reason));

        if ($user !== null) {
            $campaign->hidUsers()->attach($user->id, ['text_reason' => $text_reason]);
            return back();
        }

        return redirect(route('login'));
    }
}
