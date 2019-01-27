<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\CampaignCreationCompleted' => [
            'App\Notifications\CampaignCreationCompletedToAdmin',
        ],
        'App\Events\UserHideCampaign' => [
            'App\Notifications\UserHideCampaignNotification',
        ],
        'App\Events\ApproveCampaignEvent' => [
            'App\Notifications\ApproveCampaignNotification',
        ],
        'App\Events\DeclineCampaignEvent' => [
            'App\Notifications\DeclineCampaignNotification',
        ],
        'App\Events\UserLoggedInEvent' => [
            'App\Listeners\CreateLoginItemInDBListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
