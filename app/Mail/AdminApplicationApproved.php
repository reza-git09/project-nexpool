<?php

namespace App\Mail;

use App\Models\AdminApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminApplicationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AdminApplication $application,
        public ?string $plainPassword = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[NEXPOOL] Pengajuan Akun Admin Disetujui',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_approved',
        );
    }
}
