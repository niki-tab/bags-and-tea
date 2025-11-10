<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Src\Products\Certificates\Infrastructure\Eloquent\CertificateEloquentModel;

class AuthenticationCertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public CertificateEloquentModel $certificate,
        public string $pdfPath,
        public string $certificateLocale = 'en'
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Set locale for subject translation
        app()->setLocale($this->certificateLocale);

        return new Envelope(
            subject: __('certificates.email_subject', ['app_name' => config('app.name')]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Set the locale for translations in the view
        app()->setLocale($this->certificateLocale);

        return new Content(
            view: 'emails.authentication-certificate',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfPath)
                ->as('Certificate-' . $this->certificate->certificate_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
