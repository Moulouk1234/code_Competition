<?php

namespace App\Notifications;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewsletterNotification extends Notification
{
    public $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Newsletter Subject')
            ->line('Hello ' . $notifiable->name)
            ->line($this->content);
    }

    // Add the via method
    public function via($notifiable)
    {
        return ['mail']; // You can add other channels like 'database', 'slack', etc.
    }
}
