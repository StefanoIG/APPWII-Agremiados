<?php

namespace App\Http\Controllers;

use App\Models\PaymentReceipt;
use App\Models\UserSubscription;
use App\Notifications\PaymentReceiptUploaded;
use App\Notifications\PaymentReceiptReviewed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Mostrar comprobantes pendientes de revisión (admin/secretaria)
     */
    public function pending()
    {
        $pendingReceipts = PaymentReceipt::with(['userSubscription.user', 'userSubscription.plan'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payments.pending', compact('pendingReceipts'));
    }

    /**
     * Mostrar un comprobante específico
     */
    public function show(PaymentReceipt $receipt)
    {
        $receipt->load(['userSubscription.user', 'userSubscription.plan', 'reviewer']);
        return view('payments.show', compact('receipt'));
    }

    /**
     * Subir comprobante de pago (usuario regular)
     */
    public function upload(Request $request, UserSubscription $subscription)
    {
        $request->validate([
            'receipt_file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'user_notes' => 'nullable|string|max:500'
        ]);

        // Verificar que la suscripción pertenezca al usuario actual
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para subir comprobantes a esta suscripción.');
        }

        // Verificar que la suscripción esté pendiente
        if ($subscription->status !== 'pending') {
            return redirect()->back()->with('error', 'Solo puedes subir comprobantes a suscripciones pendientes.');
        }

        // Verificar si ya hay un comprobante aprobado
        $approvedReceipt = $subscription->paymentReceipts()->where('status', 'approved')->first();
        if ($approvedReceipt) {
            return redirect()->back()->with('error', 'Esta suscripción ya tiene un comprobante aprobado.');
        }

        // Verificar si ya hay un comprobante pendiente
        $pendingReceipt = $subscription->paymentReceipts()->where('status', 'pending')->first();
        if ($pendingReceipt) {
            return redirect()->back()->with('error', 'Ya tienes un comprobante pendiente de revisión para esta suscripción.');
        }

        // Guardar el archivo
        $file = $request->file('receipt_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('payment_receipts', $fileName, 'public');

        // Crear el registro del comprobante
        $receipt = PaymentReceipt::create([
            'user_subscription_id' => $subscription->id,
            'user_id' => Auth::id(),
            'receipt_image' => $filePath,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'user_notes' => $request->user_notes,
            'status' => 'pending'
        ]);

        // Notificar a administradores y secretarias
        $admins = User::role(['admin', 'secretaria'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new PaymentReceiptUploaded($receipt, $admin->name));
        }

        return redirect()->back()->with('success', 'Comprobante subido exitosamente. Será revisado por nuestro equipo.');
    }

    /**
     * Aprobar un comprobante de pago
     */
    public function approve(Request $request, PaymentReceipt $receipt)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        // Verificar que esté pendiente
        if ($receipt->status !== 'pending') {
            return redirect()->back()->with('error', 'Este comprobante ya ha sido procesado.');
        }

        // Verificar si ya hay un comprobante aprobado para esta suscripción
        $existingApproved = PaymentReceipt::where('user_subscription_id', $receipt->user_subscription_id)
            ->where('status', 'approved')
            ->where('id', '!=', $receipt->id)
            ->exists();

        if ($existingApproved) {
            // Si ya hay un comprobante aprobado, rechazar este automáticamente
            $receipt->update([
                'status' => 'rejected',
                'admin_notes' => 'Esta suscripción ya tiene un comprobante aprobado. No se pueden aprobar múltiples pagos para la misma suscripción.',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id()
            ]);

            return redirect()->back()->with('error', 'Esta suscripción ya tiene un pago aprobado. El comprobante ha sido rechazado automáticamente.');
        }

        // Aprobar el comprobante
        $receipt->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        // Activar la suscripción solo si no está ya activa
        $subscription = $receipt->userSubscription;
        if ($subscription->status !== 'active') {
            $startDate = now();
            $endDate = $subscription->plan->duration_type === 'monthly' 
                ? $startDate->copy()->addMonth() 
                : $startDate->copy()->addYear();

            $subscription->update([
                'status' => 'active',
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
        }

        // Rechazar automáticamente otros comprobantes pendientes para esta suscripción
        PaymentReceipt::where('user_subscription_id', $receipt->user_subscription_id)
            ->where('status', 'pending')
            ->where('id', '!=', $receipt->id)
            ->update([
                'status' => 'rejected',
                'admin_notes' => 'Rechazado automáticamente: ya se aprobó otro comprobante para esta suscripción.',
                'reviewed_at' => now(),
                'reviewed_by' => Auth::id()
            ]);

        // Notificar al usuario
        $receipt->user->notify(new PaymentReceiptReviewed($receipt, 'approved'));

        return redirect()->back()->with('success', 'Pago aprobado exitosamente. La suscripción ha sido activada.');
    }

    /**
     * Rechazar un comprobante de pago
     */
    public function reject(Request $request, PaymentReceipt $receipt)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        // Verificar que esté pendiente
        if ($receipt->status !== 'pending') {
            return redirect()->back()->with('error', 'Este comprobante ya ha sido procesado.');
        }

        // Rechazar el comprobante
        $receipt->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        // Notificar al usuario
        $receipt->user->notify(new PaymentReceiptReviewed($receipt, 'rejected'));

        return redirect()->back()->with('success', 'Pago rechazado. El usuario ha sido notificado.');
    }

    /**
     * Ver archivo del comprobante
     */
    public function viewReceipt(PaymentReceipt $receipt)
    {
        $filePath = storage_path('app/public/' . $receipt->receipt_image);

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado.');
        }

        return response()->file($filePath);
    }

    /**
     * Obtener estadísticas de pagos (para dashboard)
     */
    public function stats()
    {
        $stats = [
            'pending' => PaymentReceipt::where('status', 'pending')->count(),
            'approved' => PaymentReceipt::where('status', 'approved')->count(),
            'rejected' => PaymentReceipt::where('status', 'rejected')->count(),
            'total_amount_approved' => PaymentReceipt::where('status', 'approved')->sum('amount'),
            'receipts_this_month' => PaymentReceipt::whereMonth('created_at', now()->month)->count(),
            'recent_receipts' => PaymentReceipt::with(['userSubscription.user', 'userSubscription.plan'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        return response()->json($stats);
    }
}
