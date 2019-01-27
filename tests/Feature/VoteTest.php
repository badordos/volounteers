<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\City;
use App\Models\Step;
use App\Models\Voting;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoteTest extends TestCase
{

    use DatabaseMigrations;

    public $user;
    public $city;
    public $voting;
    public $campaign;
    public $step;

    public function setup()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->city = factory(City::class)->create();
        $this->campaign = factory(Campaign::class)->create([
            'city_id' => $this->city->id,
        ]);
        $this->step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
            'active' => 1,
        ]);
        $this->voting = factory(Voting::class)->create([
            'step_id' => $this->step->id,
        ]);
    }

    public function testUserCanSeeVote()
    {
        //user get vote page
        $response = $this->get(route('vote'));

        //user can see vote
        $response
            ->assertOk()
            ->assertSee(str_limit($this->voting->title, $limit = 50, $end = '...'))
            ->assertSee(str_limit($this->voting->description, $limit = 140, $end = '...'))
            ->assertSee($this->voting->step->campaign->image);
    }

    public function testForAuthUserVotesSortByJoined()
    {
        //кампания к которой присоединился юзер
        $campaign = factory(Campaign::class)->create();
        $step = factory(Step::class)->create([
            'campaign_id' => $campaign->id,
            'active' => 1,
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id
        ]);
        $campaign->joinedUsers()->attach($this->user);

        //кампания в городе пользователя
        $campaign2 = factory(Campaign::class)->create([
            'city_id' => $this->user->city_id,
        ]);
        $step2 = factory(Step::class)->create([
            'campaign_id' => $campaign2->id,
            'active' => 1,
        ]);
        $voting2 = factory(Voting::class)->create([
            'step_id' => $step2->id
        ]);

        $response = $this->actingAs($this->user)->get(route('vote'))
            ->assertOk();

        $response->assertSeeInOrder([str_limit($voting->title, $limit = 50, $end = '...'),
            str_limit($voting2->title, $limit = 50, $end = '...'),
            str_limit($this->voting->title, $limit = 50, $end = '...'),]);
    }

    public function testUserCanVoteInJoinedCampaignVoting()
    {
        $this->campaign->joinedUsers()->attach($this->user);

        $this->actingAs($this->user)->get(route('vote'))
            ->assertStatus(200)
            ->assertDontSee(':disabled="true"');

        $this->post(route('voting', ['voting_id' => $this->voting->id, 'user_id' => $this->user->id]),
            ['selectedVoteVariant' => rand(0, 3)]);

        $this->actingAs($this->user)->get(route('vote'))
            ->assertSee(':disabled="true"');
    }

    public function testGuestCanNotVote()
    {
        $this->post(route('voting', ['voting_id' => $this->voting->id, 'user_id' => rand(1, 10)]),
            ['selectedVoteVariant' => rand(0, 3)])
            ->assertRedirect(route('login'));
    }

    public function testUserCanNotVoteIfNotJoined()
    {
        $this->actingAs($this->user)->get(route('vote'));

        $this->post(route('voting', ['voting_id' => $this->voting->id, 'user_id' => $this->user->id]),
                ['selectedVoteVariant' => rand(0, 3)]);

        $this->actingAs($this->user)->get(route('vote'))
            ->assertDontSee(':disabled="true"');

    }

}
