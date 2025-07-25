<?php

namespace App\Http\Controllers;

use App\Models\PaymentReceipt;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PaymentReceiptUploaded;
use App\Notifications\PaymentReceiptReviewed;
use App\Models\User;

class PaymentController extends Controller
{
    /**
     * Mostrar formulario para subir comprobante de pago
     */
    public function uploadForm(UserSubscription $subscription)
    {
        // Verificar que el usuario es dueño de la suscripción
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.upload', compact('subscription'));
    }

    /**
     * Procesar la subida del comprobante de pago
     */
    public function upload(Request $request, UserSubscription $subscription)
    {
        $request->validate([
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date|before_or_equal:today'
        ]);

        // Verificar que el usuario es dueño de la suscripción
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        // Subir archivo
        $path = $request->file('receipt_image')->store('payment_receipts', 'public');

        // Crear registro del recibo
        $receipt = PaymentReceipt::create([
            'user_subscription_id' => $subscription->id,
            'user_id' => Auth::id(),
            'receipt_image' => $path,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'status' => 'pending'
        ]);

        // Enviar notificaciones
        $this->sendNotifications($receipt);

        return redirect()->route('subscriptions.my-subscriptions')
            ->with('success', 'Comprobante de pago enviado. Será revisado en las próximas 48 horas.');
    }

    /**
     * Panel de administración para revisar pagos
     */
    public function manage()
    {
        $pendingReceipts = PaymentReceipt::with(['user', 'userSubscription.subscriptionPlan'])
            ->pending()
            ->latest()
            ->get();

        $allReceipts = PaymentReceipt::with(['user', 'userSubscription.subscriptionPlan', 'reviewer'])
            ->latest()
            ->paginate(20);

        return view('payments.manage', compact('pendingReceipts', 'allReceipts'));
    }

    /**
     * Aprobar comprobante de pago
     */
    public function approve(Request $request, PaymentReceipt $receipt)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $receipt->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        // Activar la suscripción
        $receipt->userSubscription->update(['status' => 'active']);

        // Enviar notificación al usuario
        $receipt->user->notify(new PaymentReceiptReviewed($receipt, 'approved'));

        return redirect()->back()
            ->with('success', 'Pago aprobado y suscripción activada.');
    }

    /**
     * Rechazar comprobante de pago
     */
    public function reject(Request $request, PaymentReceipt $receipt)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        $receipt->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        // Enviar notificación al usuario
        $receipt->user->notify(new PaymentReceiptReviewed($receipt, 'rejected'));

        return redirect()->back()
            ->with('success', 'Pago rechazado.');
    }

    /**
     * Ver comprobante de pago
     */
    public function viewReceipt(PaymentReceipt $receipt)
    {
        // Verificar permisos
        if (Auth::user()->hasRole(['admin', 'secretaria']) || $receipt->user_id === Auth::id()) {
            return response()->file(storage_path('app/public/' . $receipt->receipt_image));
        }

        abort(403);
    }

    /**
     * Enviar notificaciones por email
     */
    private function sendNotifications(PaymentReceipt $receipt)
    {
        // Notificar a secretarias
        $secretarias = User::role('secretaria')->get();
        foreach ($secretarias as $secretaria) {
            $secretaria->notify(new PaymentReceiptUploaded($receipt));
        }

        // Notificar al usuario
        $receipt->user->notify(new PaymentReceiptUploaded($receipt, true));
    }
}
