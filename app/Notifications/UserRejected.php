<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRejected extends Notification
{
    use Queueable;

    protected $motivo;

    /**
     * Create a new notification instance.
     */
    public function __construct($motivo = null)
    {
        $this->motivo = $motivo;
    }

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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Solicitud de registro rechazada')
                    ->greeting('Hola ' . $notifiable->name)
                    ->line('Lamentamos informarte que tu solicitud de registro ha sido rechazada.')
                    ->when($this->motivo, function ($mail) {
                        return $mail->line('**Motivo:** ' . $this->motivo);
                    })
                    ->line('Si tienes alguna pregunta o deseas más información, no dudes en contactarnos.')
                    ->line('Puedes intentar registrarte nuevamente corrigiendo los datos necesarios.')
                    ->action('Registrarse Nuevamente', url('/register'))
                    ->salutation('Saludos cordiales');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Tu solicitud de registro ha sido rechazada',
            'motivo' => $this->motivo,
            'rejected_at' => now()
        ];
    }
}