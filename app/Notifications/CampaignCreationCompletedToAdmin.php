<?php

namespace App\Notifications;

use App\Events\CampaignCreationCompleted;
use App\Models\Campaign;
use App\Models\Role;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CampaignCreationCompletedToAdmin extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var Campaign
     */
    public $campaign;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign, User $user)
    {
        $this->campaign = $campaign;
        $this->user = $user;
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
                    ->line('User created new campaign "' . $this->campaign->title . '", click to moderate.')
                    ->action('To moderate', url('/nova/resources/campaigns'));
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
     * Handle the event here
     */
    public function handle(CampaignCreationCompleted $event)
    {
        $this->campaign = $event->campaign;
        $this->user = $event->user;

        $admins = Role::admins();
        foreach($admins as $admin){
            app(\Illuminate\Contracts\Notifications\Dispatcher::class)
                ->sendNow($admin, $this);
        }
    }
}
