<?php

namespace Tests\Feature;

use App\Models\Block;
use App\Models\Campaign;
use App\Models\Role;
use App\Models\Step;
use App\Models\Voting;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MainPageTest extends TestCase
{

    use DatabaseMigrations;

    public $campaign;
    public $step;
    public $voting;

    public function setup()
    {
        parent::setUp();

        $this->campaign = factory(Campaign::class)->create();
        $this->step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
            'active' => 1,
        ]);
        $this->voting = factory(Voting::class)->create([
            'step_id' => $this->step->id,
        ]);
    }

    public function testUserCanSeeCampaignsSortByPopular()
    {
        $user = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create();
        $campaign->joinedUsers()->attach($user);

        $this->actingAs($user)->get(route('main'))
            ->assertOk()
            ->assertSee($this->campaign->title)
            ->assertSee($campaign->title)
            ->assertSee(str_limit($this->campaign->description, $limit = 170, $end = '...'))
            ->assertSee(str_limit($campaign->description, $limit = 170, $end = '...'))
            ->assertSeeInOrder([$campaign->title, $this->campaign->title,]);
    }

    public function testAuthUserCanSeeActiveStepVotingAndDontSeeNoActiveStepVoting()
    {
        $user = factory(User::class)->create();
        $step = factory(Step::class)->create([
            'campaign_id' => $this->campaign->id,
            'active' => 0,
        ]);
        $voting = factory(Voting::class)->create([
            'step_id' => $step->id,
        ]);

        $this->actingAs($user)->get(route('main'))
            ->assertSee(str_limit($this->voting->title, $limit = 50, $end = '...'))
            ->assertSee(str_limit($this->voting->description, $limit = 140, $end = '...'))
            ->assertDontSee($voting->title)
            ->assertDontSee($voting->description);
    }

    public function testUserCanSeeHowItWorksAndAboutAndTeamBlocks()
    {
        $howItWorks = factory(Block::class)->create([
            'type' => 'howItWorks',
        ]);
        $about = factory(Block::class)->create([
            'type' => 'about',
        ]);
        $member = factory(Block::class)->create([
            'type' => 'team',
        ]);

        $this->get(route('main'))
            ->assertSee($howItWorks->img)
            ->assertSee($about->img)
            ->assertSee($about->content)
            ->assertSee($member->title)
            ->assertSee($member->img)
            ->assertSee($member->content);

        //аналогично проверяем страницу about-us
        $this->get(route('about-us'))
            ->assertSee($about->img)
            ->assertSee($about->content)
            ->assertSee($member->title)
            ->assertSee($member->img)
            ->assertSee($member->content);
    }

    public function testAdminCanGetMainPageForGuestAndUserDontCan()
    {
        $admin = factory(User::class)->create();
        $adminRole = factory(Role::class)->create([
            'title' => 'admin'
        ]);
        $admin->roles()->attach($adminRole);
        $user = factory(User::class)->create();
        $block = factory(Block::class)->create([
            'type' => 'team'
        ]);

        $this->actingAs($user)->get(route('main.guest'))
            ->assertStatus(403);

        $this->actingAs($admin)->get(route('main.guest'))
            ->assertOk()
            ->assertSee($block->title)
            ->assertSee($block->content)
            ->assertSee($block->img);
    }
}
