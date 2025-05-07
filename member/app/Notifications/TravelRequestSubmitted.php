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
        $user = $this->travelRequest->user;
        return (new MailMessage)
            ->subject('New Travel Request Submitted by ' . $user->name)
            ->greeting('Hello Admin,')
            ->line($user->name . ' submitted a ' . ucfirst($this->travelRequest->type) . ' travel request.')
            ->line('Travel Dates: ' . $this->travelRequest->intended_departure_date . ' to ' . $this->travelRequest->intended_return_date)
            ->action('View Request', route('travel-requests.show', $this->travelRequest->id))
            ->line('Thank you for managing the travel system!');
    }

    public function toDatabase($notifiable)
    {
        $user = $this->travelRequest->user;

        return [
            'title' => '🧳 Travel Request by ' . $user->name,
            'message' => $user->name . ' submitted a ' . ucfirst($this->travelRequest->type) . ' travel request.',
            'url' => route('travel-requests.show', $this->travelRequest->id),
            'icon' => '🧳',
            'type' => 'travel-request',
        ];
    }

    public function toBroadcast($notifiable)
    {
        $user = $this->travelRequest->user;

        return new BroadcastMessage([
            'title' => '🧳 Travel Request by ' . $user->name,
            'message' => $user->name . ' submitted a ' . ucfirst($this->travelRequest->type) . ' travel request.',
            'url' => route('travel-requests.show', $this->travelRequest->id),
            'icon' => '🧳',
            'type' => 'travel-request',
        ]);
    }
}
