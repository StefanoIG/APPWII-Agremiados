<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
                    ->subject('¡Tu cuenta ha sido aprobada!')
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('¡Excelentes noticias! Tu cuenta ha sido aprobada por nuestro equipo.')
                    ->line('Ya puedes acceder al sistema con tus credenciales.')
                    ->action('Iniciar Sesión', url('/login'))
                    ->line('Gracias por formar parte de nuestra comunidad.')
                    ->salutation('¡Bienvenido/a!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Tu cuenta ha sido aprobada',
            'approved_at' => now()
        ];
    }
}