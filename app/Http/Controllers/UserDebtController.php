<?php

namespace App\Http\Controllers;

use App\Models\UserDebt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserDebtController extends Controller
{
    /**
     * Mostrar deudas del usuario actual
     */
    public function myDebts()
    {
        $user = Auth::user();
        
        // Verificar si es admin o secretaria
        if ($user->roles->contains('name', 'admin') || $user->roles->contains('name', 'secretaria')) {
            // Para admin y secretaria, mostrar vista especial sin deudas
            return view('user-debts.admin-view');
        }
        
        $debts = $user->debts()->with('monthlyCut')->orderBy('due_date', 'desc')->get();
        
        $stats = [
            'total_pending' => $user->getTotalPendingDebt(),
            'pending_count' => $user->getPendingDebts()->count(),
            'overdue_count' => $user->getOverdueDebts()->count(),
        ];

        return view('user-debts.my-debts', compact('debts', 'stats'));
    }

    /**
     * Mostrar formulario para subir comprobante de pago
     */
    public function payDebt(UserDebt $debt)
    {
        // Verificar que la deuda pertenezca al usuario actual
        if ($debt->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a esta deuda.');
        }

        return view('debts.pay', compact('debt'));
    }

    /**
     * Procesar el pago de una deuda (método simplificado)
     */
    public function pay(Request $request, UserDebt $debt)
    {
        // Verificar que la deuda pertenezca al usuario actual
        if ($debt->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a esta deuda.');
        }

        $request->validate([
            'payment_method' => 'required|string',
            'receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);

        // Subir el comprobante
        $file = $request->file('receipt');
        $fileName = 'payment_receipts/' . uniqid() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('public', $fileName);

        // Marcar la deuda con el comprobante subido
        $debt->update([
            'receipt_url' => Storage::url($fileName),
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'paid_at' => now(),
            'status' => 'pending_approval', // Pendiente de aprobación
        ]);

        return redirect()->route('user-debts.my-debts')
                        ->with('success', 'Comprobante de pago subido exitosamente. Pendiente de aprobación por la secretaría.');
    }

    /**
     * Procesar el pago de una deuda
     */
    public function processPayment(Request $request, UserDebt $debt)
    {
        // Verificar que la deuda pertenezca al usuario actual
        if ($debt->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a esta deuda.');
        }

        $request->validate([
            'payment_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Subir el comprobante
        $file = $request->file('payment_receipt');
        $fileName = 'payment_receipts/' . uniqid() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('public', $fileName);

        // Marcar la deuda como pagada (pendiente de aprobación)
        $debt->update([
            'payment_receipt' => $fileName,
            'paid_at' => now(),
            // Mantener status como 'pending' hasta que la secretaria apruebe
        ]);

        return redirect()->route('debts.my-debts')
                        ->with('success', 'Comprobante de pago subido exitosamente. Pendiente de aprobación por la secretaría.');
    }

    /**
     * Vista para la secretaria - gestionar pagos
     */
    public function managePayments()
    {
        $debts = UserDebt::with(['user', 'monthlyCut'])
                         ->whereNotNull('payment_receipt')
                         ->where('status', 'pending')
                         ->orderBy('paid_at', 'desc')
                         ->paginate(15);

        return view('debts.manage-payments', compact('debts'));
    }

    /**
     * Aprobar un pago
     */
    public function approvePayment(UserDebt $debt)
    {
        $debt->markAsPaid($debt->payment_receipt);

        return redirect()->back()
                        ->with('success', 'Pago aprobado exitosamente.');
    }

    /**
     * Rechazar un pago
     */
    public function rejectPayment(Request $request, UserDebt $debt)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        // Eliminar el comprobante subido
        if ($debt->payment_receipt) {
            Storage::delete('public/' . $debt->payment_receipt);
        }

        $debt->update([
            'payment_receipt' => null,
            'paid_at' => null,
            'status' => 'pending',
        ]);

        // Aquí podrías enviar un email al usuario explicando el rechazo
        
        return redirect()->back()
                        ->with('success', 'Pago rechazado. El usuario ha sido notificado.');
    }

    /**
     * Vista para la secretaria - resumen de todas las deudas
     */
    public function debtsSummary()
    {
        $stats = [
            'total_debts' => UserDebt::count(),
            'pending_debts' => UserDebt::pending()->count(),
            'paid_debts' => UserDebt::paid()->count(),
            'overdue_debts' => UserDebt::overdue()->count(),
            'total_pending_amount' => UserDebt::where('status', '!=', 'paid')->sum('amount'),
            'total_paid_amount' => UserDebt::paid()->sum('amount'),
        ];

        $recentDebts = UserDebt::with(['user', 'monthlyCut'])
                              ->orderBy('created_at', 'desc')
                              ->limit(10)
                              ->get();

        return view('debts.summary', compact('stats', 'recentDebts'));
    }

    /**
     * Marcar deudas vencidas automáticamente
     */
    public function markOverdueDebts()
    {
        $overdueDebts = UserDebt::where('status', 'pending')
                               ->where('due_date', '<', now())
                               ->get();

        foreach ($overdueDebts as $debt) {
            $debt->markAsOverdue();
        }

        return redirect()->back()
                        ->with('success', count($overdueDebts) . ' deudas marcadas como vencidas.');
    }
}
