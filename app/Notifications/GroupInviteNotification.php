<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Relative\LaravelExpoPushNotifications\ExpoPushNotifications;
use Relative\LaravelExpoPushNotifications\PushNotification;

class GroupInviteNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($group_name, $owner_name)
    {
        $this->group_name = $group_name;
        $this->owner_name = $owner_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ExpoPushNotifications::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toExpoPushNotification($notifiable)
    {
        return (new PushNotification)
            ->title('New Group Invitation 🎉')
            ->body("Exciting news! You've been invited by {$this->owner_name} to join {$this->group_name}. Check out the invite on the Groups page!");
    }
}
