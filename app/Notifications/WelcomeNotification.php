<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Relative\LaravelExpoPushNotifications\ExpoPushNotifications;
use Relative\LaravelExpoPushNotifications\PushNotification;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
            ->title('Welcome to MyBowling App 🎳')
            ->body("We're thrilled to welcome you to MyBowling, where the excitement of the lanes meets the convenience of your device! Get ready for a striking experience as you embark on a journey filled with fun, challenges, and friendly competition.");
    }


}
