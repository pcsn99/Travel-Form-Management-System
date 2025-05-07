<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TravelRequestApproved extends Notification
{
    use Queueable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toArray($notifiable)
    {
        return [
            'message' => '✅ Your ' . ucfirst($this->request->type) . ' travel request has been approved.',
            'url' => route('travel-requests.show', $this->request->id),
        ];
    }

    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Travel Request was Approved')
            ->line('Your ' . ucfirst($this->request->type) . ' travel request has been approved.')
            ->action('View Request', route('travel-requests.show', $this->request->id));
    }
}
