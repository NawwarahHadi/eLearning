<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TutorApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

   public function __construct(public $user, public $password, public $subjects) {}
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to Al-Amin Tuition Centre - Application Approved');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.tutor_approved');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
