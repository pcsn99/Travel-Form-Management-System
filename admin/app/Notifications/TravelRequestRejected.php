<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TravelRequestDeclined extends Notification
{
    use Queueable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Added 'mail' so emails can be sent too
    }

    public function toArray($notifiable)
    {
        $url = rtrim(config('app.member_url'), '/') . '/travel-requests/' . $this->request->id;

        return [
            'message' => '❌ Your ' . ucfirst($this->request->type) . ' travel request was declined.',
            'url' => $url,
        ];
    }

    public function toMail($notifiable)
    {
        $url = rtrim(config('app.member_url'), '/') . '/travel-requests/' . $this->request->id;

        return (new MailMessage)
            ->subject('Travel Request Declined')
            ->line('We regret to inform you that your ' . ucfirst($this->request->type) . ' travel request has been declined.')
            ->action('View Request', $url);
    }
}
