<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TutorRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Update regarding your application - Al-Amin Tuition Centre');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.tutor_rejected');
    }
}
