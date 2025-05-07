<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageText;

    /**
     * Create a new message instance.
     */
    public function __construct($messageText = 'This is a test email from Laravel using Outlook SMTP.')
    {
        $this->messageText = $messageText;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Test Email from Laravel')
                    ->view('emails.test')
                    ->with([
                        'messageText' => $this->messageText,
                    ]);
    }
}
