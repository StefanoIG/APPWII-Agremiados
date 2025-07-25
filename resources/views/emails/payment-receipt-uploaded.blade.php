@component('mail::message')
# Comprobante de Pago Subido

Hola {{ $adminName }},

Se ha subido un nuevo comprobante de pago que requiere tu revisión.

## Detalles del Pago

**Usuario:** {{ $paymentReceipt->userSubscription->user->name }}  
**Email:** {{ $paymentReceipt->userSubscription->user->email }}  
**Plan:** {{ $paymentReceipt->userSubscription->plan->name }}  
**Monto:** ${{ number_format($paymentReceipt->amount, 0) }}  
**Fecha de Pago:** {{ $paymentReceipt->payment_date->format('d/m/Y') }}  

@if($paymentReceipt->user_notes)
**Notas del Usuario:**  
{{ $paymentReceipt->user_notes }}
@endif

@component('mail::button', ['url' => route('payments.show', $paymentReceipt)])
Revisar Comprobante
@endcomponent

Por favor, revisa el comprobante lo antes posible para activar la suscripción del usuario.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
