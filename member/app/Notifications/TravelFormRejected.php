<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TravelFormDeclined extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Form Declined')
                    ->line('A notification regarding TravelFormDeclined.')
                    ->action('View Details', url('/dashboard'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Form Declined',
            'message' => 'A notification about TravelFormDeclined.',
            'url' => '/dashboard'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Form Declined',
            'message' => 'A notification about TravelFormDeclined.',
            'url' => '/dashboard'
        ]);
    }
}
