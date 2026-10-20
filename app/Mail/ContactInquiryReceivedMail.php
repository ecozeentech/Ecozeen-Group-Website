<?php

namespace App\Mail;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactInquiryReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ContactInquiry $inquiry)
    {
    }

    public function build(): self
    {
        return $this->subject('New contact inquiry: '.$this->inquiry->full_name)
            ->markdown('emails.contact-inquiry-received', ['inquiry' => $this->inquiry]);
    }
}
