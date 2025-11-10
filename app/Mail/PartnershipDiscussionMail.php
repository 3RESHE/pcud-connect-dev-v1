<?php

namespace App\Mail;

use App\Models\Partnership;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartnershipDiscussionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $partnership;
    public $message;
    public $adminName;

    public function __construct(Partnership $partnership, string $message, string $adminName = null)
    {
        $this->partnership = $partnership;
        $this->message = $message;
        $this->adminName = $adminName ?? auth()->user()->name ?? 'PCU-DASMA Admin';
    }

    public function build()
    {
        return $this->markdown('emails.partnership-discussion')
            ->subject('Discussion Regarding Your Partnership Proposal - ' . $this->partnership->activity_title)
            ->to($this->partnership->contact_email)
            ->from(config('mail.from.address'), config('mail.from.name'));
    }
}
