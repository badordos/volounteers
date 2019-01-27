<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\City;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignsTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    protected $campaign;
    protected $city;
    protected $category;

    public function setup()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->city = factory(City::class)->create();
        $this->category = factory(Category::class)->create();

        $this->campaign = factory(Campaign::class)->create([
            'city_id' => $this->city->id,
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
        ]);
    }

    function testUserSeeCampaigns()
    {
        $response = $this->get(route('campaigns'));

        $response->assertSee($this->campaign->title);
        $response->assertSee(str_limit($this->campaign->description, $limit = 170, $end = '...'));
        $response->assertSee($this->campaign->image);
    }

    function testCampaignsInViewSuccessOrder()
    {
        $campaigns = collect();
        $campaigns->push($this->campaign);

        sleep(1);

        $campaign = factory(Campaign::class)->create();
        $campaigns->push($campaign);

        $this->assertEquals($campaigns->pluck('id'), $campaigns->sortBy('created_at')->pluck('id'),
            'The Campaigns are not being ordered by created_at date');

        $response = $this->get(route('campaigns'));

        $response->assertSeeInOrder([$campaign->title, $this->campaign->title]);

    }

}
