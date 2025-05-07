<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TravelRequestRejected extends Notification
{
    use Queueable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['database']; // You can add 'mail' if needed
    }

    public function toArray($notifiable)
    {
        return [
            'message' => '❌ Your ' . ucfirst($this->request->type) . ' travel request was rejected.',
            'url' => route('travel-requests.show', $this->request->id),
        ];
    }

    // Optional: enable if you decide to use email
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Travel Request Rejected')
            ->line('We regret to inform you that your ' . ucfirst($this->request->type) . ' travel request has been rejected.')
            ->action('View Request', route('travel-requests.show', $this->request->id));
    }
}
