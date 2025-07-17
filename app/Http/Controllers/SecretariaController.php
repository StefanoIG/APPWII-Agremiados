<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserApproved;
use App\Notifications\UserRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SecretariaController extends Controller
{
    public function index()
    {
        $pendingUsersCount = User::where('is_active', false)->count();
        return view('secretaria.dashboard', compact('pendingUsersCount'));
    }
    
    /**
     * Mostrar usuarios pendientes de aprobación
     */
    public function usuariosPendientes()
    {
        $usuarios = User::where('is_active', false)
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);
                       
        return view('secretaria.usuarios-pendientes', compact('usuarios'));
    }
    
    /**
     * Mostrar detalles de un usuario específico
     */
    public function mostrarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        return view('secretaria.detalle-usuario', compact('usuario'));
    }
    
    /**
     * Aprobar usuario
     */
    public function aprobarUsuario(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        
        $usuario->update([
            'is_active' => true,
            'updated_at' => now()
        ]);
        
        // Enviar notificación al usuario
        $usuario->notify(new UserApproved());
        
        return redirect()->route('secretaria.usuarios-pendientes')
                        ->with('success', 'Usuario aprobado exitosamente.');
    }
    
    /**
     * Rechazar usuario
     */
    public function rechazarUsuario(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);
        
        $usuario = User::findOrFail($id);
        $motivo = $request->input('motivo');
        
        // Enviar notificación de rechazo
        $usuario->notify(new UserRejected($motivo));
        
        // Eliminar archivos si existen
        if ($usuario->titulo_pdf) {
            Storage::disk('public')->delete($usuario->titulo_pdf);
        }
        if ($usuario->qrpdt) {
            Storage::disk('public')->delete($usuario->qrpdt);
        }
        
        // Eliminar usuario
        $usuario->delete();
        
        return redirect()->route('secretaria.usuarios-pendientes')
                        ->with('success', 'Usuario rechazado y eliminado del sistema.');
    }
}
