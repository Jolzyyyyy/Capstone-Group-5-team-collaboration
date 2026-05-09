<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminClientInvitation extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $inviteUrl
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Complete your Printify & Co. admin client setup')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A developer pre-registered your admin client account.')
            ->line('Use the secure invite link below to set your password and complete your reference profile before approval.')
            ->action('Complete Admin Client Setup', $this->inviteUrl)
            ->line('If you did not expect this invitation, you can ignore this email.');
    }
}
