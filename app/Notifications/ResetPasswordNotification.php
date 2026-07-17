<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private string $url) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Password — Dausyaf Portfolio')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Kamu menerima email ini karena ada permintaan reset password untuk akunmu.')
            ->action('Reset Password', $this->url)
            // action() = tombol di email dengan text dan URL
            ->line('Link ini akan kadaluarsa dalam 60 menit.')
            ->line('Jika kamu tidak merasa meminta reset password, abaikan email ini.')
            ->salutation('Salam, Tim Dausyaf Portfolio');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
