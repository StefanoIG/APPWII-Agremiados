<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Verificar si el usuario tiene alguno de los roles requeridos
        foreach ($roles as $role) {
            if ($user->roles->contains('name', $role)) {
                return $next($request);
            }
        }

        // Si no tiene ningún rol requerido, redirigir al dashboard
        abort(403, 'No tienes permisos para acceder a esta página.');
    }
}
