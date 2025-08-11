<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserDebts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si es admin o secretaria, permitir acceso
        if ($user && $user->hasAnyRole(['admin', 'secretaria'])) {
            return $next($request);
        }

        // Si el usuario tiene deudas pendientes, redirigir a página de deudas
        if ($user && $user->hasPendingDebts()) {
            return redirect()->route('debts.my-debts')
                           ->with('warning', 'Debes ponerte al día con tus pagos para acceder a las actividades del gremio.');
        }

        return $next($request);
    }
}
