<?php

namespace App\Events;

use App\Models\Campaign;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserHideCampaign
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $campaign;
    public $text_reason;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign, $text_reason)
    {
        $this->campaign = $campaign;
        $this->text_reason = $text_reason;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
