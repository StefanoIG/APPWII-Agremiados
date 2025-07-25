@component('mail::message')
# ¡Tu Pago ha sido Aprobado! 🎉

Hola {{ $paymentReceipt->userSubscription->user->name }},

¡Excelentes noticias! Tu comprobante de pago ha sido aprobado y tu suscripción está ahora activa.

## Detalles de tu Suscripción

**Plan:** {{ $paymentReceipt->userSubscription->plan->name }}  
**Monto:** ${{ number_format($paymentReceipt->amount, 0) }}  
**Fecha de Inicio:** {{ $paymentReceipt->userSubscription->start_date->format('d/m/Y') }}  
**Fecha de Vencimiento:** {{ $paymentReceipt->userSubscription->end_date->format('d/m/Y') }}  

@if($paymentReceipt->admin_notes)
**Comentarios del Administrador:**  
{{ $paymentReceipt->admin_notes }}
@endif

## ¿Qué sigue ahora?

Tu suscripción {{ $paymentReceipt->userSubscription->plan->duration_type === 'monthly' ? 'mensual' : 'anual' }} está activa y podrás acceder a todos los beneficios incluidos en tu plan.

@component('mail::button', ['url' => route('home')])
Acceder a Mi Cuenta
@endcomponent

### Información Importante

- Tu suscripción se renovará automáticamente el {{ $paymentReceipt->userSubscription->end_date->format('d/m/Y') }}
- Recibirás un recordatorio antes del vencimiento
- Puedes cambiar tu plan en cualquier momento desde tu panel de usuario

Si tienes alguna pregunta, no dudes en contactarnos.

¡Gracias por ser parte de nuestra comunidad!

Saludos,<br>
{{ config('app.name') }}
@endcomponent
