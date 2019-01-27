<?php

namespace App\Notifications;

use App\Events\UserHideCampaign;
use App\Models\Campaign;
use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserHideCampaignNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $campaign;

    public $text_reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
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
            ->line('User hid campaign "' . $this->campaign->title . '", click to see this.')
            ->line('Reason: ' . $this->text_reason . '.')
            ->action('To campaign', url(route('single-campaign', $this->campaign)));
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
     * @param  UserHideCampaign  $event
     * @return void
     */
    public function handle(UserHideCampaign $event)
    {
        $this->text_reason = $event->text_reason;
        $this->campaign = $event->campaign;

        $admins = Role::admins();
        foreach($admins as $admin){
            app(\Illuminate\Contracts\Notifications\Dispatcher::class)
                ->sendNow($admin, $this);
        }
    }
}
