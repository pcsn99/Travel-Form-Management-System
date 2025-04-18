<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TravelRequestSubmitted extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Request Submitted')
                    ->line('A notification regarding TravelRequestSubmitted.')
                    ->action('View Details', url('/dashboard'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Request Submitted',
            'message' => 'A notification about TravelRequestSubmitted.',
            'url' => '/dashboard'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Request Submitted',
            'message' => 'A notification about TravelRequestSubmitted.',
            'url' => '/dashboard'
        ]);
    }
}
