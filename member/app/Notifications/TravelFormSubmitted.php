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
        $user = $this->form->request->user;

        return (new MailMessage)
            ->subject('📄 New ' . ucfirst($this->type) . ' Travel Form Submitted by ' . $user->name)
            ->greeting('Hello Admin,')
            ->line($user->name . ' has submitted a ' . ucfirst($this->type) . ' travel form.')
            ->line('Submitted for: ' . $this->form->request->destination)
            ->line('Travel Dates: ' . $this->form->request->intended_departure_date . ' to ' . $this->form->request->intended_return_date)
            ->action('Review Travel Form', $this->formUrl())
            ->line('Thank you for reviewing travel forms promptly.');
    }

    public function toDatabase($notifiable)
    {
        $user = $this->form->request->user;

        return [
            'title' => '📄 Travel Form by ' . $user->name,
            'message' => $user->name . ' submitted a ' . ucfirst($this->type) . ' travel form.',
            'url' => $this->formUrl(),
            'icon' => '📄',
            'type' => 'travel-form',
        ];
    }

    public function toBroadcast($notifiable)
    {
        $user = $this->form->request->user;

        return new BroadcastMessage([
            'title' => '📄 Travel Form by ' . $user->name,
            'message' => $user->name . ' submitted a ' . ucfirst($this->type) . ' travel form.',
            'url' => $this->formUrl(),
            'icon' => '📄',
            'type' => 'travel-form',
        ]);
    }

    protected function formUrl()
    {
        return url('/' . ($this->type === 'local' ? 'local-forms' : 'Overseas-forms') . '/' . $this->form->id);
    }
}
