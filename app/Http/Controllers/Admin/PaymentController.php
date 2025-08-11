<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('role:admin'); // Comentado temporalmente
    }

    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        // Simulamos algunos pagos para mostrar la interfaz
        $payments = collect([
            (object) [
                'id' => 1,
                'user_name' => 'Juan Pérez',
                'user_email' => 'juan@example.com',
                'amount' => 50.00,
                'status' => 'completed',
                'payment_method' => 'credit_card',
                'created_at' => now()->subDays(2),
                'subscription_plan' => 'Plan Básico'
            ],
            (object) [
                'id' => 2,
                'user_name' => 'María García',
                'user_email' => 'maria@example.com',
                'amount' => 100.00,
                'status' => 'pending',
                'payment_method' => 'bank_transfer',
                'created_at' => now()->subDays(1),
                'subscription_plan' => 'Plan Premium'
            ],
            (object) [
                'id' => 3,
                'user_name' => 'Carlos López',
                'user_email' => 'carlos@example.com',
                'amount' => 75.00,
                'status' => 'failed',
                'payment_method' => 'credit_card',
                'created_at' => now(),
                'subscription_plan' => 'Plan Intermedio'
            ]
        ]);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the specified payment.
     */
    public function show($id)
    {
        // Simulamos un pago específico
        $payment = (object) [
            'id' => $id,
            'user_name' => 'Juan Pérez',
            'user_email' => 'juan@example.com',
            'amount' => 50.00,
            'status' => 'completed',
            'payment_method' => 'credit_card',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
            'subscription_plan' => 'Plan Básico',
            'transaction_id' => 'TXN_' . str_pad($id, 8, '0', STR_PAD_LEFT),
            'notes' => 'Pago procesado correctamente'
        ];

        return view('admin.payments.show', compact('payment'));
    }
}
