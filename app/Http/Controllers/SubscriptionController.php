<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Models\PaymentReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        
        return view('subscriptions.plans', compact('plans', 'userSubscription'));
    }

    /**
     * Mostrar planes de suscripción
     * Admin/Secretaria: Vista de gestión para CRUD
     * Usuario regular: Vista de selección para suscribirse
     */
    public function plans()
    {
        $user = Auth::user();
        
        // Si es admin o secretaria, mostrar vista de gestión
        if ($user->hasAnyRole(['admin', 'secretaria'])) {
            $plans = SubscriptionPlan::all(); // Mostrar todos los planes (activos e inactivos)
            return view('subscriptions.admin.plans', compact('plans'));
        }
        
        // Para usuarios regulares, solo mostrar planes activos
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('subscriptions.plans', compact('plans'));
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
        $user = Auth::user();

        // Verificar si ya tiene una suscripción activa
        if ($user->hasActiveSubscription()) {
            return redirect()->route('subscriptions.plans')
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

        return redirect()->route('subscriptions.show', $subscription)
            ->with('success', 'Suscripción creada exitosamente. Realiza el pago y sube tu comprobante.');
    }

    /**
     * Mostrar detalles de una suscripción específica
     */
    public function show(UserSubscription $subscription)
    {
        // Verificar que el usuario sea propietario de la suscripción
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver esta suscripción.');
        }

        return view('subscriptions.show', compact('subscription'));
    }

    /**
     * Mostrar suscripciones del usuario
     */
    public function mySubscriptions()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Debes estar autenticado');
            }
            
            // Obtener las suscripciones usando la relación existente
            $subscriptions = UserSubscription::where('user_id', $user->id)
                                           ->with(['plan', 'paymentReceipts'])
                                           ->latest()
                                           ->get();
            
            return view('subscriptions.my-subscriptions', compact('subscriptions'));
        } catch (\Exception $e) {
            Log::error('Error en mySubscriptions: ' . $e->getMessage());
            return back()->with('error', 'Error al cargar las suscripciones: ' . $e->getMessage());
        }
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

    /**
     * Crear nuevo plan de suscripción (solo admin/secretaria)
     */
    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:monthly,yearly,custom',
            'price' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        SubscriptionPlan::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'duration_months' => $request->duration_months,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('subscriptions.plans')->with('success', 'Plan creado exitosamente.');
    }

    /**
     * Actualizar plan de suscripción (solo admin/secretaria)
     */
    public function updatePlan(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:monthly,yearly,custom',
            'price' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $plan->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'duration_months' => $request->duration_months,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('subscriptions.plans')->with('success', 'Plan actualizado exitosamente.');
    }

    /**
     * Alternar estado activo/inactivo del plan
     */
    public function togglePlan(SubscriptionPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        
        $status = $plan->is_active ? 'activado' : 'desactivado';
        return redirect()->route('subscriptions.plans')->with('success', "Plan {$status} exitosamente.");
    }
}
