<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Str;

class TravelFormRejected extends Notification
{
    use Queueable;

    protected $form;

    public function __construct($form)
    {
        $this->form = $form;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        $type = Str::lower($this->form->request->type);
        $url = rtrim(config('app.member_url'), '/') . "/{$type}-forms/{$this->form->id}/view";

        return (new MailMessage)
            ->subject('❌ Travel Form Rejected')
            ->line('Your ' . ucfirst($type) . ' travel form has been rejected.')
            ->action('View Details', $url)
            ->line('Please review the form and make necessary corrections if applicable.');
    }

    public function toDatabase($notifiable)
    {
        $type = Str::lower($this->form->request->type);
        $url = rtrim(config('app.member_url'), '/') . "/{$type}-forms/{$this->form->id}/view";

        return [
            'title' => 'Travel Form Rejected',
            'message' => '❌ Your ' . ucfirst($type) . ' travel form was rejected.',
            'url' => $url,
        ];
    }

    public function toBroadcast($notifiable)
    {
        $type = Str::lower($this->form->request->type);
        $url = rtrim(config('app.member_url'), '/') . "/{$type}-forms/{$this->form->id}/view";

        return new BroadcastMessage([
            'title' => 'Travel Form Rejected',
            'message' => '❌ Your ' . ucfirst($type) . ' travel form was rejected.',
            'url' => $url,
        ]);
    }
}
