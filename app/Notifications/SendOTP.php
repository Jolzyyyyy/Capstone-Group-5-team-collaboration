<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOTP extends Notification
{
    public $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Security Verification Code - Printify & Co.')
            ->view([
                'html' => 'emails.otp-simple',
                'text' => 'emails.otp-simple-text',
            ], [
                'otp' => $this->otp,
                'name' => $notifiable->name ?? 'Customer',
                'ttlMinutes' => User::EMAIL_OTP_TTL_MINUTES,
                'appName' => config('app.name'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp_code' => $this->otp,
            'sent_at' => now(),
        ];
    }
}
