<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TravelFormSubmitted extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Form Submitted')
                    ->line('A notification regarding TravelFormSubmitted.')
                    ->action('View Details', url('/dashboard'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Form Submitted',
            'message' => 'A notification about TravelFormSubmitted.',
            'url' => '/dashboard'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Form Submitted',
            'message' => 'A notification about TravelFormSubmitted.',
            'url' => '/dashboard'
        ]);
    }
}
