<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Role;
use App\Notifications\ApproveCampaignNotification;
use App\Notifications\DeclineCampaignNotification;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModerationTest extends TestCase
{
    use DatabaseMigrations;

    public $admin;
    public $adminRole;

    public function setup()
    {
        parent::setUp();

        $this->admin = factory(User::class)->create([
            'email' => 'forspam@flagstudio.ru',
        ]);
        $this->adminRole = factory(Role::class)->create([
            'title' => 'admin'
        ]);
        $this->admin->roles()->attach($this->adminRole);
    }

    public function testAdminCanApprovedCampaignInAdminPanel()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create([
            'user_id' => $user->id,
            'readiness' => 'moderation'
        ]);

        $this->actingAs($this->admin)->post('/nova-api/campaigns/action?action=approve-campaign', [
            'resources' => $campaign->id
        ])->assertOk();

        Notification::assertSentTo($user, ApproveCampaignNotification::class);
    }

    public function testAdminCanDeclineCampaignInAdminPanel()
    {
        Notification::fake();

        $user = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create([
            'user_id' => $user->id,
            'readiness' => 'moderation'
        ]);

        $this->actingAs($this->admin)->post('/nova-api/campaigns/action?action=decline-campaign', [
            'resources' => $campaign->id,
            'reason' => 'reason'
        ])->assertOk();

        Notification::assertSentTo($user, DeclineCampaignNotification::class);
    }

}
