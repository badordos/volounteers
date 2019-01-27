<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Campaign;
use App\Models\Category;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    public $user;

    public function setup()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
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

    public function testUserCanSeeAboutMeInfoAndJoinedCampaign()
    {
        $campaign = factory(Campaign::class)->create();
        $campaign->joinedUsers()->attach($this->user);

        $this->actingAs($this->user)->get(route('profile.about-me'))
            ->assertStatus(200)
            ->assertSee($campaign->title)
            ->assertSee(str_limit($campaign->description, $limit = 170, $end = '...'))
            ->assertSee($campaign->image)
            ->assertSee($this->user->image)
            ->assertSee(htmlspecialchars($this->user->name, ENT_QUOTES));
    }

    public function testUserCanSeeCreatedCampaignAndIntrestedCampaign()
    {
        $createdCampaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
        ]);

        $category = factory(Category::class)->create();

        $intrestedCampaign = factory(Campaign::class)->create([
            'category_id' => $category->id,
        ]);

        $this->user->categories()->attach($category->id);

        $this->actingAs($this->user)->get(route('profile.campaigns'))
            ->assertStatus(200)
            ->assertSee(str_limit($createdCampaign->title, $limit = 45, $end = '...'))
            ->assertSee(str_limit($intrestedCampaign->title, $limit = 45, $end = '...'));
    }

    public function testUserCanSeeAchievementInProfile()
    {
        $achievement = factory(Achievement::class)->create();
        $achievement1 = factory(Achievement::class)->create();

        $this->user->achievements()->attach($achievement->id);

        $this->actingAs($this->user)->get(route('profile.about-me'))
            ->assertStatus(200)
            ->assertDontSee($achievement1->title)
            ->assertDontSee($achievement1->description)
            ->assertSee($achievement->title)
            ->assertSee($achievement->description);
    }

    public function testUserCanSeeAllAchivmentsList()
    {
        $achievement = factory(Achievement::class)->create();

        $this->actingAs($this->user)->get(route('profile.achievements'))
            ->assertStatus(200)
            ->assertSee($achievement->title)
            ->assertSee($achievement->description);

    }

    public function testUserCanViewProfileOfAnotherUser()
    {
        $user = factory(User::class)->create();

        $this->get(route('public-profile', $user))
            ->assertOk()
            ->assertViewHas('user', $user);
    }

    public function testAdminCanSeeEditUserLink()
    {
        $admin = self::createAdmin();
        $this->actingAs($admin)
            ->get(route('public-profile', $this->user))
            ->assertSee('<a href="' . url('/nova/resources/users/') . '/' . $this->user->id . '">Edit User</a>');

    }

    public function testUserSeeEditButtonWhileCampaignNotDone()
    {
        $campaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => 'success'
        ]);

        $creatingCampaign = factory(Campaign::class)->create([
            'user_id' => $this->user->id,
            'readiness' => '5'
        ]);

        $campaign->joinedUsers()->attach($this->user);
        $creatingCampaign->joinedUsers()->attach($this->user);

        $this->actingAs($this->user)->get(route('profile.campaigns'))
            ->assertOk()
            ->assertSee(str_limit($campaign->title, $limit = 45, $end = '...'))
            ->assertSee(str_limit($creatingCampaign->title, $limit = 45, $end = '...'))
            ->assertSee(route('single-campaign', $campaign))
            ->assertDontSee(route('single-campaign', $creatingCampaign));
    }

}
