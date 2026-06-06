<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SecurityVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Ang variable na $otp ay magiging accessible sa blade file.
     */
    public $otp;

    /**
     * Create a new message instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Security Verification OTP',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            /**
             * BABALA: Huwag gamitin ang 'auth.verify-otp' dahil form iyon.
             * Gagamit tayo ng dedikadong email template.
             */
            view: 'emails.security-otp', 
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}