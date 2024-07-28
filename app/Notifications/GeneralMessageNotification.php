<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Relative\LaravelExpoPushNotifications\ExpoPushNotifications;
use Relative\LaravelExpoPushNotifications\PushNotification;

class GeneralMessageNotification extends Notification
{
    use Queueable;

    public $message;
    public $title;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $message)
    {
        $this->message = $message;
        $this->title = $title;
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

    public function toExpoPushNotification($notifiable)
    {
        return (new PushNotification)
            ->title($this->title)
            ->body($this->message);
    }
}
