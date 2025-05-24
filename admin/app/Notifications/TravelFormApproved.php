<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Str;

class TravelFormApproved extends Notification
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
            ->subject('✅ Travel Form Approved')
            ->line('Your ' . ucfirst($type) . ' travel form has been approved.')
            ->action('View Form', $url);
    }
    
    public function toDatabase($notifiable)
    {
        $type = Str::lower($this->form->request->type);
        $url = rtrim(config('app.member_url'), '/') . "/{$type}-forms/{$this->form->id}/view";

        return [
            'title' => 'Travel Form Approved',
            'message' => '✅ Your ' . ucfirst($type) . ' travel form has been approved.',
            'url' => $url,
        ];
    }
    
    public function toBroadcast($notifiable)
    {
        $type = Str::lower($this->form->request->type);
        $url = rtrim(config('app.member_url'), '/') . "/{$type}-forms/{$this->form->id}/view";

        return new BroadcastMessage([
            'title' => 'Travel Form Approved',
            'message' => '✅ Your ' . ucfirst($type) . ' travel form has been approved.',
            'url' => $url,
        ]);
    }
}
