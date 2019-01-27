<?php

namespace App\Notifications;

use App\Events\DeclineCampaignEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DeclineCampaignNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $campaign;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Your campaign "' . $this->campaign->title . '" was decline.')
            ->line('Reason: ' . $this->campaign->reason . '.')
            ->action('To edit this campaign', url(route('create-campaign-step-1')));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Handle the event.
     *
     * @param  DeclineCampaignEvent  $event
     * @return void
     */
    public function handle(DeclineCampaignEvent $event)
    {
        $this->campaign = $event->campaign;

        app(\Illuminate\Contracts\Notifications\Dispatcher::class)
            ->sendNow($this->campaign->user, $this);
    }
}
