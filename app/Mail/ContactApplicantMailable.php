<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactApplicantMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant;
    public $partner;
    public $jobPosting;
    public $subject;
    public $messageContent;

    /**
     * Create a new message instance.
     */
    public function __construct($applicant, $partner, $jobPosting, $subject, $messageContent)
    {
        $this->applicant = $applicant;
        $this->partner = $partner;
        $this->jobPosting = $jobPosting;
        $this->subject = $subject;
        $this->messageContent = $messageContent;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.contact-applicant')
                    ->with([
                        'applicant' => $this->applicant,
                        'partner' => $this->partner,
                        'jobPosting' => $this->jobPosting,
                        'messageContent' => $this->messageContent,
                    ]);
    }
}
