<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     * Redirige a los usuarios sin suscripción activa a la página de planes
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Si no hay usuario autenticado, continuar normalmente
        if (!$user) {
            return $next($request);
        }
        
        // Si es admin o secretaria, no verificar suscripción
        if ($user->hasAnyRole(['admin', 'secretaria'])) {
            return $next($request);
        }
        
        // Rutas que no requieren suscripción
        $excludedRoutes = [
            'subscriptions.plans',
            'subscriptions.subscribe',
            'subscriptions.my',
            'payments.upload',
            'profile.show',
            'home',
            'logout'
        ];
        
        // Si la ruta actual está excluida, continuar
        if (in_array($request->route()->getName(), $excludedRoutes)) {
            return $next($request);
        }
        
        // Si el usuario no tiene suscripción activa, redirigir a planes
        if (!$user->hasActiveSubscription()) {
            return redirect()->route('subscriptions.plans')
                ->with('warning', 'Para acceder a esta funcionalidad necesitas una suscripción activa. Selecciona un plan a continuación.');
        }
        
        return $next($request);
    }
}
