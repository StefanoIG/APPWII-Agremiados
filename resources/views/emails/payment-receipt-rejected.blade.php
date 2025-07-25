@component('mail::message')
# Comprobante de Pago Rechazado

Hola {{ $paymentReceipt->userSubscription->user->name }},

Lamentamos informarte que tu comprobante de pago ha sido rechazado y necesita corrección.

## Detalles del Pago

**Plan:** {{ $paymentReceipt->userSubscription->plan->name }}  
**Monto Enviado:** ${{ number_format($paymentReceipt->amount, 0) }}  
**Monto Requerido:** ${{ number_format($paymentReceipt->userSubscription->plan->price, 0) }}  
**Fecha de Pago:** {{ $paymentReceipt->payment_date->format('d/m/Y') }}  

## Motivo del Rechazo

{{ $paymentReceipt->admin_notes }}

## ¿Qué puedes hacer ahora?

1. **Revisar el motivo:** Asegúrate de entender por qué fue rechazado tu comprobante
2. **Corregir el problema:** Realiza los ajustes necesarios según las observaciones
3. **Subir nuevo comprobante:** Sube un nuevo comprobante que cumpla con los requisitos

@component('mail::button', ['url' => route('subscriptions.my')])
Subir Nuevo Comprobante
@endcomponent

### Consejos para un Comprobante Válido

- Asegúrate de que el monto coincida exactamente con el precio del plan
- La imagen debe ser clara y legible
- Incluye toda la información de la transacción
- Verifica que la fecha de pago sea reciente

### Información del Plan

**{{ $paymentReceipt->userSubscription->plan->name }}**  
- Precio: ${{ number_format($paymentReceipt->userSubscription->plan->price, 0) }}
- Duración: {{ $paymentReceipt->userSubscription->plan->duration_type === 'monthly' ? 'Mensual' : 'Anual' }}

Si tienes alguna pregunta sobre el rechazo o necesitas ayuda, no dudes en contactarnos.

Saludos,<br>
{{ config('app.name') }}
@endcomponent
