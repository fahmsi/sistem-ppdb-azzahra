<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusPendaftaranNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $status)
    {
        $this->message = $message;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail']; // Can add 'database' or custom channels like 'wa'
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->status === 'diterima' ? 'Selamat! Pendaftaran Diterima' : 'Update Status Pendaftaran';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Assalamu\'alaikum ' . $notifiable->name . ',')
            ->line('Ada pembaruan status pendaftaran anak Anda di PAUD Az-Zahra:')
            ->line('**Status:** ' . strtoupper($this->status))
            ->line('**Pesan:** ' . $this->message)
            ->action('Cek Dashboard', url('/login'))
            ->line('Terima kasih telah mempercayakan pendidikan anak Anda kepada kami.');
    }
}
