<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PaymentReceipt;

class PaymentReceiptReviewed extends Notification
{
    use Queueable;

    public $receipt;
    public $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(PaymentReceipt $receipt, $action)
    {
        $this->receipt = $receipt;
        $this->action = $action; // 'approved' or 'rejected'
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
        $message = new MailMessage();
        
        if ($this->action === 'approved') {
            $message->subject('¡Pago Aprobado! - Agremiados')
                ->greeting('¡Excelente noticia, ' . $this->receipt->user->name . '!')
                ->line('Tu comprobante de pago ha sido aprobado.')
                ->line('Tu suscripción está ahora activa.')
                ->line('Monto aprobado: $' . number_format($this->receipt->amount, 2));
                
            if ($this->receipt->admin_notes) {
                $message->line('Notas del administrador: ' . $this->receipt->admin_notes);
            }
            
            $message->action('Ver Mi Suscripción', url('/subscriptions/my-subscriptions'))
                ->line('¡Gracias por ser parte de nuestra comunidad!');
        } else {
            $message->subject('Pago Rechazado - Agremiados')
                ->greeting('Hola ' . $this->receipt->user->name)
                ->line('Lamentablemente, tu comprobante de pago ha sido rechazado.')
                ->line('Monto: $' . number_format($this->receipt->amount, 2));
                
            if ($this->receipt->admin_notes) {
                $message->line('Motivo del rechazo: ' . $this->receipt->admin_notes);
            }
            
            $message->line('Por favor, verifica tu comprobante y vuelve a enviarlo.')
                ->action('Subir Nuevo Comprobante', url('/subscriptions/my-subscriptions'))
                ->line('Si tienes dudas, no dudes en contactarnos.');
        }
        
        return $message;
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
            'action' => $this->action,
            'amount' => $this->receipt->amount,
            'admin_notes' => $this->receipt->admin_notes
        ];
    }
}