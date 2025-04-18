<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

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
        return ['database']; // We'll add email later
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Your travel request ('.$this->request->type.') has been approved!',
        ];
    }
}
