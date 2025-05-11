<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TravelFormRejected extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Form Rejected')
                    ->line('A notification regarding TravelFormRejected.')
                    ->action('View Details', url('/dashboard'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Form Rejected',
            'message' => 'A notification about TravelFormRejected.',
            'url' => '/dashboard'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Form Rejected',
            'message' => 'A notification about TravelFormRejected.',
            'url' => '/dashboard'
        ]);
    }
}
