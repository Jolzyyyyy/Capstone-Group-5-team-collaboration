<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // Pwede kang magpasa ng details dito kung gusto mo (e.g., IP address)
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
            ->subject('Security Alert: Password Changed - ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is an automated notification to inform you that your account password has been successfully changed.')
            ->line('If you initiated this change, you can safely ignore this email.')
            ->action('Login to Your Account', route('login'))
            ->line('**SECURITY WARNING:** If you did not authorize this change, please contact our support team immediately to secure your account.')
            ->salutation('Stay secure, the ' . config('app.name') . ' Team');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event' => 'password_changed',
            'timestamp' => now(),
        ];
    }
}