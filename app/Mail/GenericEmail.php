<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Generic Email Mailable
 * Used for sending generic emails (registration, password reset, etc.)
 */
class GenericEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailHtml;

    /**
     * Create a new message instance.
     */
    public function __construct($emailHtml, $subject)
    {
        $this->emailHtml = $emailHtml;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->html($this->emailHtml);
    }
}
