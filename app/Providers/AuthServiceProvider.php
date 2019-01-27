<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Login' => 'App\Policies\ActivityPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', 'App\Policies\AppPolicy@admin');

        Gate::define('creator', 'App\Policies\CampaignPolicy@creator');
        Gate::define('delete', 'App\Policies\CampaignPolicy@delete');
        Gate::define('author', 'App\Policies\CampaignPolicy@author');
        Gate::define('createVotingInStep', 'App\Policies\CampaignPolicy@createVotingInStep');
    }
}
