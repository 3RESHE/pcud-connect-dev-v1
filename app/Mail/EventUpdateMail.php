<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $event;
    public $subject;
    public $message;
    public $recipientName;

    public function __construct(Event $event, string $subject, string $message, string $recipientName = 'Participant')
    {
        $this->event = $event;
        $this->subject = $subject;
        $this->message = $message;
        $this->recipientName = $recipientName;
    }

    public function envelope()
    {
        return new Envelope(
            subject: $this->subject,
            from: config('mail.from.address'),
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.event-update',
            with: [
                'event' => $this->event,
                'message' => $this->message,
                'recipientName' => $this->recipientName,
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}
