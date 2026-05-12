<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $otp, public int $expiryMinutes = 5) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Verifikasi Email Anda - Bayn');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.verify_email');
    }
}
