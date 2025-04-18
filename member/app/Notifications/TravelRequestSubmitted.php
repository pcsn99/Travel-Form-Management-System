<?php

namespace App\Notifications;

use App\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TravelRequestSubmitted extends Notification
{
    use Queueable;

    protected $travelRequest;

    public function __construct(TravelRequest $travelRequest)
    {
        $this->travelRequest = $travelRequest;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Travel Request Submitted')
            ->line('A community member has submitted a travel request.')
            ->action('View Request', route('travel-requests.show', $this->travelRequest->id));
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Travel Request Submitted',
            'message' => 'A community member submitted a new travel request.',
            'url' => route('travel-requests.show', $this->travelRequest->id),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'New Travel Request Submitted',
            'message' => 'A community member submitted a new travel request.',
            'url' => route('travel-requests.show', $this->travelRequest->id),
        ]);
    }
}
