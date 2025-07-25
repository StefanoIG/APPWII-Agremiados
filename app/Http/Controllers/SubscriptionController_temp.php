<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\PaymentReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Mostrar planes de suscripción disponibles
     */
    public function index()
    {
        $plans = SubscriptionPlan::active()->get();
        $userSubscription = Auth::user()->activeSubscription();
        
        return view('subscriptions.index', compact('plans', 'userSubscription'));
    }

    /**
     * Mostrar formulario para seleccionar plan
     */
    public function choosePlan(SubscriptionPlan $plan)
    {
        return view('subscriptions.choose-plan', compact('plan'));
    }

    /**
     * Crear nueva suscripción
     */
    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'payment_method' => 'required|in:monthly,yearly'
        ]);

        $user = Auth::user();

        // Verificar si ya tiene una suscripción activa
        if ($user->hasActiveSubscription()) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Ya tienes una suscripción activa.');
        }

        // Crear nueva suscripción
        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addMonths($plan->duration_months);

        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'pending',
            'amount_paid' => $plan->price,
            'notes' => 'Suscripción creada - Pendiente de pago'
        ]);

        return redirect()->route('payments.upload', $subscription)
            ->with('success', 'Suscripción creada. Por favor, sube tu comprobante de pago.');
    }

    /**
     * Mostrar suscripciones del usuario
     */
    public function mySubscriptions()
    {
        $user = Auth::user();
        $subscriptions = $user->subscriptions()->with('subscriptionPlan', 'paymentReceipts')->latest()->get();
        
        return view('subscriptions.my-subscriptions', compact('subscriptions'));
    }

    /**
     * Panel de administración de suscripciones (para admin/secretaria)
     */
    public function manage()
    {
        $pendingSubscriptions = UserSubscription::with(['user', 'subscriptionPlan'])
            ->pending()
            ->latest()
            ->get();

        $allSubscriptions = UserSubscription::with(['user', 'subscriptionPlan'])
            ->latest()
            ->paginate(20);

        return view('subscriptions.manage', compact('pendingSubscriptions', 'allSubscriptions'));
    }

    /**
     * Activar suscripción
     */
    public function activate(UserSubscription $subscription)
    {
        $subscription->update(['status' => 'active']);

        return redirect()->back()
            ->with('success', 'Suscripción activada correctamente.');
    }

    /**
     * Cancelar suscripción
     */
    public function cancel(UserSubscription $subscription)
    {
        $subscription->update(['status' => 'cancelled']);

        return redirect()->back()
            ->with('success', 'Suscripción cancelada.');
    }
}
