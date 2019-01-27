<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;

class RefreshDatabase extends Controller
{
    public function __invoke()
    {
        if(!app()->environment('production'))
            \Artisan::call('migrate:fresh', [
                '--force' => true,
                '--seed' => true,
            ]);
        \Artisan::call('migrate', [
            '--force' => true,
            '--path' => 'vendor/laravel/nova/database/migrations/'
        ]);

        \Artisan::call('migrate', [
            '--force' => true,
            '--path' => '/vendor/laravel/telescope/src/Storage/migrations/'
        ]);

        return back();
    }
}
