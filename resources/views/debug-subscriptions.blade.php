<!DOCTYPE html>
<html>
<head>
    <title>Debug Suscripciones</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .debug-info { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; }
        .subscription { background: #e8f5e8; padding: 10px; margin: 5px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Debug de Suscripciones</h1>
    
    <div class="debug-info">
        <h3>Usuario Actual:</h3>
        <p><strong>ID:</strong> {{ $debug['user_id'] }}</p>
        <p><strong>Nombre:</strong> {{ $debug['user_name'] }}</p>
        <p><strong>Activo:</strong> {{ $debug['user_active'] ? 'SÍ' : 'NO' }}</p>
        <p><strong>Total suscripciones:</strong> {{ $debug['total_subscriptions'] }}</p>
        <p><strong>Suscripciones activas:</strong> {{ $debug['active_subscriptions'] }}</p>
        <p><strong>Tiene suscripción activa:</strong> {{ $debug['has_active_subscription'] ? 'SÍ' : 'NO' }}</p>
        <p><strong>Puede participar:</strong> {{ $debug['can_participate'] ? 'SÍ' : 'NO' }}</p>
    </div>
    
    <h3>Todas las Suscripciones:</h3>
    @foreach($subscriptions as $sub)
        <div class="subscription">
            <p><strong>Usuario:</strong> {{ $sub->user->name }}</p>
            <p><strong>Estado:</strong> {{ $sub->status }}</p>
            <p><strong>Fecha fin:</strong> {{ $sub->end_date }}</p>
            <p><strong>Es activa:</strong> {{ $sub->isActive() ? 'SÍ' : 'NO' }}</p>
        </div>
    @endforeach
</body>
</html>
