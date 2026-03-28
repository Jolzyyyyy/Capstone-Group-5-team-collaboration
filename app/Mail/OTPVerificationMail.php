<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OTPVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The OTP code to be sent.
     * Making this public allows it to be automatically available in the view.
     */
    public $otp;

    /**
     * Create a new message instance.
     * Accepts the OTP code from the Controller/Service.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     * Defines the subject line as seen by the user.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') . ' - Security Verification Code',
        );
    }

    /**
     * Get the message content definition.
     * Points to your English Markdown template at resources/views/emails/otp.blade.php.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.otp', 
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