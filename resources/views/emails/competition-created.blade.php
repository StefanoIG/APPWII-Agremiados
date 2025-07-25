<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Competencia Disponible</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .title {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .competition-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        .info-value {
            color: #6c757d;
        }
        .cta-button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .cta-button:hover {
            background-color: #218838;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-open {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏆 Agremiados</div>
            <p>Sistema de Gestión Deportiva</p>
        </div>

        <h1 class="title">¡Nueva Competencia Disponible!</h1>

        <p>Estimado/a <strong>{{ $user->name }}</strong>,</p>

        <p>Nos complace informarte que se ha creado una nueva competencia en la que puedes participar:</p>

        <div class="competition-info">
            <h3 style="color: #28a745; margin-top: 0;">{{ $competition->name }}</h3>
            
            <div class="info-item">
                <span class="info-label">🏃 Disciplina:</span>
                <span class="info-value">{{ $competition->disciplina->name ?? 'No especificada' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">🏷️ Categoría:</span>
                <span class="info-value">{{ $competition->categoria->name ?? 'No especificada' }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">👥 Miembros por equipo:</span>
                <span class="info-value">{{ $competition->members_per_team }} jugadores</span>
            </div>

            <div class="info-item">
                <span class="info-label">📊 Máximo de miembros:</span>
                <span class="info-value">{{ $competition->max_members }} personas</span>
            </div>

            <div class="info-item">
                <span class="info-label">🏁 Fecha de inicio:</span>
                <span class="info-value">{{ $competition->start_date->format('d/m/Y') }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">📅 Fecha de fin:</span>
                <span class="info-value">{{ $competition->end_date ? $competition->end_date->format('d/m/Y') : 'Por definir' }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">⏰ Límite de inscripción:</span>
                <span class="info-value">{{ $competition->registration_deadline->format('d/m/Y H:i') }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">📝 Estado:</span>
                <span class="status-badge status-open">{{ ucfirst($competition->status) }}</span>
            </div>
        </div>

        @if($competition->description)
            <div class="highlight">
                <strong>📋 Descripción:</strong><br>
                {{ $competition->description }}
            </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $competitionUrl }}" class="cta-button">
                🎯 Ver Competencia y Unirse
            </a>
        </div>

        <div class="highlight">
            <strong>⚠️ Importante:</strong> Para participar en esta competencia, necesitas tener una suscripción activa. 
            Si aún no tienes una, puedes adquirirla desde tu panel de usuario.
        </div>

        <p>¡No pierdas esta oportunidad de demostrar tus habilidades y competir con otros agremiados!</p>

        <div class="footer">
            <p>Este es un mensaje automático del Sistema de Agremiados.</p>
            <p>Si no deseas recibir estas notificaciones, puedes configurarlo desde tu perfil.</p>
            <p>&copy; {{ date('Y') }} Sistema de Agremiados. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
