<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomerVerifyEmail extends Notification
{
    use Queueable;

    /**
     * Determine the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Your Email - Printify & Co.')
            ->greeting('Kamusta, ' . $notifiable->name . '!')
            ->line('Salamat sa pag-sign up sa Printify & Co. Konting hakbang na lang at pwede ka na mag-order.')
            ->action('I-verify ang Email', url('/customer/verify/' . $notifiable->id . '/' . sha1($notifiable->getEmailForVerification())))
            ->line('Kung hindi ikaw ang gumawa ng account na ito, balewalain lang ang email na ito.');
    }
}