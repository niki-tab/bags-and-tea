<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class InterestingDealsFound extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $listings
    ) {
    }

    public function envelope(): Envelope
    {
        $count = $this->listings->count();
        return new Envelope(
            subject: "Vinted Deals Found! ({$count} " . ($count === 1 ? 'item' : 'items') . ")",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.interesting-deals-found',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
