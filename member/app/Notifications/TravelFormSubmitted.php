<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TravelFormSubmitted extends Notification
{
    use Queueable;

    protected $form;
    protected $request;
    protected $type;

    public function __construct($form, $request)
    {
        $this->form = $form;
        $this->request = $request;
        $this->type = $form instanceof \App\Models\LocalTravelForm ? 'local' : 'overseas';
    }

    public function via($notifiable)
    {
        return ['database', 'mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New ' . ucfirst($this->type) . ' Travel Form Submitted')
            ->line("A {$this->type} travel form has been submitted by a community member.")
            ->action('View Form', $this->formUrl());
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Travel Form Submitted',
            'message' => "A {$this->type} travel form has been submitted.",
            'url' => $this->formUrl(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Travel Form Submitted',
            'message' => "A {$this->type} travel form has been submitted.",
            'url' => $this->formUrl(),
        ]);
    }

    protected function formUrl()
    {
        
        return url('/' . ($this->type === 'local' ? 'local-forms' : 'Overseas-forms') . '/' . $this->form->id);
    }
}
