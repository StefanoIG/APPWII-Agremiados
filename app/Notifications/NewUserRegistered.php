<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
                    ->subject('Nuevo Usuario Registrado - Pendiente de Aprobación')
                    ->greeting('¡Hola!')
                    ->line('Se ha registrado un nuevo usuario en el sistema que requiere aprobación.')
                    ->line('**Datos del usuario:**')
                    ->line('Nombre: ' . $this->user->name)
                    ->line('Email: ' . $this->user->email)
                    ->line('Fecha de registro: ' . $this->user->created_at->format('d/m/Y H:i'))
                    ->action('Revisar Solicitud', url('/secretaria/usuarios-pendientes'))
                    ->line('Por favor, revisa la información del usuario y aprueba o rechaza su registro.')
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
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
        ];
    }
}