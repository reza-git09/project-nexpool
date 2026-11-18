<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminLoginOtpMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public string $code,
        public int $minutes,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Login Admin Kolam NEXPOOL',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_login_otp',
        );
    }
}
