<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nuevo Corte Mensual</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            border-radius: 0 0 5px 5px;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        .details {
            background-color: white;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏛️ Nuevo Corte Mensual</h1>
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="content">
        <h2>Estimado/a {{ $user->name }},</h2>
        
        <p>Te informamos que se ha generado un nuevo corte mensual:</p>

        <div class="details">
            <h3>📋 Detalles del Corte</h3>
            <p><strong>Nombre:</strong> {{ $monthlyCut->cut_name }}</p>
            <p><strong>Fecha de corte:</strong> {{ $monthlyCut->cut_date->format('d/m/Y') }}</p>
            <p><strong>Fecha de vencimiento:</strong> {{ $monthlyCut->cut_date->addDays(30)->format('d/m/Y') }}</p>
            
            @if($monthlyCut->description)
                <p><strong>Descripción:</strong> {{ $monthlyCut->description }}</p>
            @endif
        </div>

        <div class="amount">
            💰 Monto a pagar: ${{ number_format($monthlyCut->amount, 0, ',', '.') }}
        </div>

        <div class="alert">
            <strong>⚠️ Importante:</strong> Tienes 30 días calendario para realizar el pago. 
            Después de esta fecha, tu acceso a las actividades del gremio será restringido hasta que te pongas al día.
        </div>

        <p><strong>📝 Instrucciones para el pago:</strong></p>
        <ol>
            <li>Realiza la transferencia o pago por el monto indicado</li>
            <li>Ingresa a tu panel de usuario en la plataforma</li>
            <li>Ve a la sección "Mis Deudas"</li>
            <li>Sube el comprobante de pago</li>
            <li>Espera la aprobación de la secretaría</li>
        </ol>

        <div style="text-align: center;">
            <a href="{{ route('debts.my-debts') }}" class="btn">Ver Mis Deudas</a>
        </div>

        <p>Si tienes alguna pregunta o necesitas más información, no dudes en contactar a la secretaría del gremio.</p>

        <p>Saludos cordiales,<br>
        <strong>Administración del Gremio</strong></p>
    </div>

    <div class="footer">
        <p>Este es un mensaje automático, por favor no respondas a este email.</p>
        <p>© {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
    </div>
</body>
</html>
