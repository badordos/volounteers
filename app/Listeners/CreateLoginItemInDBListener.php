<?php

namespace App\Listeners;

use App\Models\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateLoginItemInDBListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $login = new Login([
            'screen' => $event->request->screen,
            'browser' => $event->request->header('User-Agent'),
            'ip' => $event->request->ip(),
            'cookie' => session()->getId(),
        ]);
        $login->user()->associate(auth()->user());
        $login->save();
    }
}
