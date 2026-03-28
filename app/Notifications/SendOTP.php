<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue; // Naka-comment muna para instant send
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOTP extends Notification
{
    use Queueable;

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
            ->greeting('Hello, ' . ($notifiable->name ?? 'Customer') . '!')
            ->line('We need to verify your account to ensure the security of your Printify & Co. profile.')
            ->line('Here is your 6-digit verification code:')
            
            /** * Pinapalaki natin ang OTP gamit ang markdown (#) 
             * para madaling makita at ma-copy ng user.
             */
            ->line('# **' . $this->otp . '**') 
            
            ->line('This code will expire in 10 minutes.')
            ->line('If you did not request this, please ignore this email to keep your account safe.')
            ->salutation('Thank you, ' . config('app.name') . ' Team');
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