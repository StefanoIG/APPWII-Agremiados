<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PaymentReceipt;

class PaymentReceiptUploaded extends Notification
{
    use Queueable;

    public $receipt;
    public $isForUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(PaymentReceipt $receipt, $isForUser = false)
    {
        $this->receipt = $receipt;
        $this->isForUser = $isForUser;
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
        if ($this->isForUser) {
            return (new MailMessage)
                ->subject('Comprobante de Pago Recibido - Agremiados')
                ->greeting('¡Hola ' . $this->receipt->user->name . '!')
                ->line('Hemos recibido tu comprobante de pago por la suscripción.')
                ->line('Monto: $' . number_format($this->receipt->amount, 2))
                ->line('Tu pago será confirmado en las próximas 48 horas.')
                ->line('Te notificaremos por email una vez que sea procesado.')
                ->line('¡Gracias por ser parte de nuestra comunidad!');
        } else {
            return (new MailMessage)
                ->subject('Nuevo Comprobante de Pago para Revisar - Agremiados')
                ->greeting('¡Hola!')
                ->line('Se ha subido un nuevo comprobante de pago que requiere revisión.')
                ->line('Usuario: ' . $this->receipt->user->name)
                ->line('Email: ' . $this->receipt->user->email)
                ->line('Monto: $' . number_format($this->receipt->amount, 2))
                ->line('Fecha de pago: ' . $this->receipt->payment_date->format('d/m/Y'))
                ->action('Revisar Comprobante', url('/payments/manage'))
                ->line('Por favor, revisa y procesa este pago lo antes posible.');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'receipt_id' => $this->receipt->id,
            'user_name' => $this->receipt->user->name,
            'amount' => $this->receipt->amount,
            'is_for_user' => $this->isForUser
        ];
    }
}