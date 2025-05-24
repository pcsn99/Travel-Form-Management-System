<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OverseasFormExportMail extends Mailable
{
    public $messageBody;
    public $filePath;
    public $filename;
    public $subjectText;

    public function __construct($subject, $messageBody, $filePath, $filename)
    {
        $this->subjectText = $subject;
        $this->messageBody = $messageBody;
        $this->filePath = $filePath;
        $this->filename = $filename;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->view('emails.overseas-export')
                    ->attach($this->filePath, [
                        'as' => $this->filename,
                        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->with([
                        'messageBody' => $this->messageBody,
                    ]);
    }
}