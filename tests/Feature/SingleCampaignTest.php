<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\City;
use App\Models\Step;
use App\Models\Voting;
use App\Notifications\UserHideCampaignNotification;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SingleCampaignTest extends TestCase
{
    use DatabaseMigrations;

    protected $campaign;
    protected $user;
    protected $city;
    protected $category;

    public function setup()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->city = factory(City::class)->create();
        $this->category = factory(Category::class)->create();

        $this->campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'city_id' => $this->city->id,
            'category_id' => $this->category->id,
        ]);
    }

    public static function createAdmin()
    {
        $admin = factory(User::class)->create([
            'email' => 'forspam@flagstudio.ru',
        ]);
        $admin->roles()->attach(factory(\App\Models\Role::class)->create([
            'title' => 'admin'
        ]));
        return $admin;
    }

    function testUserCanSeeAllCampaignFields()
    {
        $this->get(route('single-campaign', $this->campaign))
            ->assertSee($this->campaign->title)
            ->assertSee($this->campaign->body)
            ->assertSee($this->campaign->description)
            ->assertSee($this->campaign->need_volunteers)
            ->assertSee($this->campaign->about_desc)
            ->assertSee($this->campaign->image);
    }

    function testUserCanSeeStepsAndVotingsOfCampaign()
    {
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('single-campaign', $this->campaign));

        $response->assertSee($step->title)
            ->assertSee($step->description)
            ->assertSee($step->voting->title)
            ->assertSee($step->voting->description);
    }

    function testUserCanJoinedAtCampaign()
    {
        $this->get(route('single-campaign', $this->campaign))
            ->assertSee('class="btn btn--bold">Join now</a>');

        $response = $this->actingAs($this->user)
            ->get(route('campaign.join', ['campaign' => $this->campaign, 'user' => $this->user]));

        $this->assertEquals(302, $response->getStatusCode());

        $this->assertDatabaseHas('campaign_user_joined', [
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->get(route('single-campaign', $this->campaign));

        $response->assertSee('<button type="button" class="btn btn--bold disabled">Joined</button>');
    }

    function testUserCanHideCampaign()
    {
        Notification::fake();

        $role = factory(\App\Models\Role::class)->create(['title' => 'admin']);
        $admin = factory(User::class)->create();
        $admin->roles()->attach($role);

        $this->get(route('single-campaign', $this->campaign))
            ->assertSee('<a href="#!" class="btn btn--bold" @click="openHide">Hide</a>');

        $response = $this->actingAs($this->user)
            ->post(route('campaign.hide', ['campaign' => $this->campaign, 'user' => $this->user]),
                ['text_reason' => '123123']);

        $this->assertEquals(302, $response->getStatusCode());

        $this->assertDatabaseHas('campaign_user_hid', [
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
            'text_reason' => '123123',
        ]);

        $response = $this->get(route('single-campaign', $this->campaign));

        $response->assertSee('<button type="button" class="btn btn--bold disabled">Hide</button>');

        Notification::assertSentTo($admin, UserHideCampaignNotification::class);
    }

    function testUserCanVoteOnStep()
    {

        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id
        ]);

        //user see step & voting
        $this->actingAs($this->user)
            ->get(route('single-campaign', $this->campaign))
            ->assertSee($step->title)
            ->assertSee($voting->title);

        $this->campaign->joinedUsers()->attach($this->user);

        $this->assertDatabaseHas('campaign_user_joined', [
            'campaign_id' => $this->campaign->id,
            'user_id' => $this->user->id,
        ]);

        //user can vote
        $this->post(route('voting', ['voting' => $voting->id, 'user' => $this->user]),
            ['selectedVoteVariant' => rand(0, 3)]);

        //user & voting relation
        $this->assertDatabaseHas('user_voting', [
            'user_id' => $this->user->id,
            'voting_id' => $voting->id
        ]);

        //user see voting
        $this->get(route('single-campaign', $this->campaign))
            ->assertSee($voting->title);

    }

    public function testUserCanCreateVotingInStep()
    {
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
        ]);

        $this->actingAs($this->user)->post(route('addVoting', $this->campaign), [
            'data' => [
                'title' => 'Title',
                'description' => 'Description',
                'id' => $step->id,
                'variants' => ['a', 'b', 'c', 'd'],
            ],
        ])->assertStatus(200);

        $this->get(route('single-campaign', $this->campaign))
            ->assertSee($step->voting->title)
            ->assertSee($step->voting->description);
    }


    public function testUserCanDeleteVotingInStep()
    {
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
            'active' => 1,
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id,
        ]);

        $this->get(route('single-campaign', $this->campaign))
            ->assertOk()
            ->assertSee($step->voting->title)
            ->assertSee($step->voting->description);

        $this->actingAs($this->user)->post(route('deleteVoting', $this->campaign), [
            'id' => $voting->id,
        ])->assertStatus(200);

        $this->get(route('single-campaign', $this->campaign))
            ->assertDontSee($step->voting->title)
            ->assertDontSee($step->voting->description);
    }

    public function testNoCreatorCannotAddVoting()
    {
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
        ]);

        //неавторизован
        $this->post(route('addVoting', $this->campaign), [
            'data' => [
                'title' => 'Title Voting',
                'description' => 'Description Voting',
                'id' => $step->id,
                'variants' => ['a', 'b', 'c', 'd'],
            ],
        ])->assertRedirect(route('login'));

        //авторизован под другим пользователем
        $user = factory(User::class)->create();
        $this->actingAs($user)->post(route('addVoting', $this->campaign), [
            'data' => [
                'title' => 'Title Voting',
                'description' => 'Description Voting',
                'id' => $step->id,
                'variants' => ['a', 'b', 'c', 'd'],
            ],
        ]);

        $this->get(route('single-campaign', $this->campaign))
            ->assertDontSee('Title')
            ->assertDontSee('Description');
    }

    public function testNotCreatorCanNotDeleteVoting()
    {
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id,
        ]);

        $this->post(route('deleteVoting', $this->campaign), [
            'id' => $voting->id,
        ])->assertRedirect(route('login'));

        $user = factory(User::class)->create();
        $this->actingAs($user)->post(route('deleteVoting', $this->campaign), [
            'id' => $voting->id,
        ]);

        $this->get(route('single-campaign', $this->campaign))
            ->assertSee($voting->title)
            ->assertSee($voting->description);
    }


    public function testAuthorCanChangeActiveStep()
    {
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
            'active' => 0,
        ]);

        $this->actingAs($this->user)->get(route('single-campaign', $this->campaign))
            ->assertSee(':disabled-toggle="false"');

        $this->post(route('activeStep', $step))
            ->assertRedirect(route('single-campaign', $this->campaign));

        $this->get(route('single-campaign', $this->campaign))
            ->assertSee('active-step="true"');
    }

    public function testNotACreatorCanNotChangeActiveStep()
    {
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
        ]);

        $user = factory(User::class)->create();
        $this->actingAs($user)->post(route('activeStep', $step))
            ->assertStatus(403);
    }

    public function testVotingButtonIsSeenOnlyInActiveStep()
    {
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
            'active' => 1
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id,
        ]);

        $user = factory(User::class)->create();
        $this->campaign->joinedUsers()->attach($user);

        $this->actingAs($user)->get(route('single-campaign', $this->campaign))
            ->assertSee(':disabled="false"');
    }

    public function testVotingButtonIsNotSeenInDisabledStep()
    {
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
            'active' => 0
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id,
        ]);

        $user = factory(User::class)->create();
        $this->campaign->joinedUsers()->attach($user);

        $this->actingAs($user)->get(route('single-campaign', $this->campaign))
            ->assertSee(':disabled="true"');
    }

    public function testAdminSeeEditCampaignLinkInAdminPanel()
    {
        //Создаем админа
        $admin = self::createAdmin();

        //Идем, проверяем кнопку Edit Campaign в плашке
        $this->actingAs($admin)
            ->get(route('single-campaign', $this->campaign))
            ->assertSee('<a href="'. url('/nova/resources/campaigns/') . '/' . $this->campaign->id .'">Edit Campaign</a>');
    }

    public function testNotAnAuthorCanNotSeeAddVoteButton()
    {
        $author = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create([
            'user_id' => $author->id
        ]);
        $step = factory(Step::class)->create([
            'campaign_id' => $campaign->id,
            'active' => 1
        ]);
        $step2 = factory(Step::class)->create([
            'campaign_id' => $campaign->id,
            'active' => 0
        ]);

        $this->actingAs($author)->get(route('single-campaign', $campaign))
            ->assertOk()
            ->assertSee('show-add-vote="true"');

        $this->actingAs($this->user)->get(route('single-campaign', $campaign))
            ->assertOk()
            ->assertDontSee('show-add-vote="true"');
    }

    public function testUserCanSeeVariantWhichHeChose()
    {
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id
        ]);

        $this->actingAs($this->user)
            ->get(route('single-campaign', $this->campaign))
            ->assertOk();

        $this->campaign->joinedUsers()->attach($this->user);

        $this->post(route('voting', ['voting' => $voting->id, 'user' => $this->user]),
            ['selectedVoteVariant' => 1]);

        $this->get(route('single-campaign', $this->campaign))
            ->assertOk()
            ->assertSee(':active-variant="1"');

    }

    public function testUserCanSeeJoinPopupIfNotJoinedToActiveStepInCampaign()
    {
        $user = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create([
            'user_id' => $user->id,
        ]);
        $step = factory(Step::class)->create([
            'campaign_id' => $campaign->id,
            'active' => 1,
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id,
        ]);

        $this->actingAs($this->user)->get(route('single-campaign', $campaign))
            ->assertOk()
            ->assertSee(':show-join="true"');
    }

    public function testUserCanNotSeeJoinPopupIfJoinedToCampaign()
    {
        $user = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create([
            'user_id' => $user->id,
        ]);
        $step = factory(Step::class)->create([
            'campaign_id' => $campaign->id,
            'active' => 1,
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id,
        ]);

        $campaign->joinedUsers()->attach($this->user);

        $this->actingAs($this->user)->get(route('single-campaign', $campaign))
            ->assertOk()
            ->assertDontSee(':show-join="true"');
    }

}
