<?php

namespace App\Mail;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestDocumentsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $organization;
    public $requestedDocuments;

    /**
     * Create a new message instance.
     */
    public function __construct(Organization $organization, $requestedDocuments)
    {
        $this->organization = $organization;
        $this->requestedDocuments = $requestedDocuments;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'مستندات إضافية مطلوبة - منصة التطوع',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.request-documents',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
