<?php

namespace App\Http\Controllers;

use App\Models\MonthlyCut;
use App\Models\UserDebt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\MonthlyCutCreated;

class MonthlyCutController extends Controller
{
    /**
     * Mostrar lista de cortes mensuales
     */
    public function index()
    {
        $cuts = MonthlyCut::with('creator')
                          ->orderBy('cut_date', 'desc')
                          ->paginate(10);
        
        return view('monthly-cuts.index', compact('cuts'));
    }

    /**
     * Mostrar formulario para crear nuevo corte
     */
    public function create()
    {
        return view('monthly-cuts.create');
    }

    /**
     * Guardar nuevo corte mensual
     */
    public function store(Request $request)
    {
        $request->validate([
            'cut_name' => 'required|string|max:255',
            'cut_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $cut = MonthlyCut::create([
            'cut_name' => $request->cut_name,
            'cut_date' => $request->cut_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        // Generar deudas para todos los usuarios activos
        $cut->generateDebtsForAllUsers();

        // Enviar notificación por correo a los usuarios
        $this->notifyUsersAboutNewCut($cut);

        return redirect()->route('monthly-cuts.index')
                        ->with('success', 'Corte mensual creado exitosamente y deudas generadas para todos los usuarios activos.');
    }

    /**
     * Mostrar detalles de un corte específico
     */
    public function show(MonthlyCut $monthlyCut)
    {
        $monthlyCut->load(['creator', 'userDebts.user']);
        
        $stats = [
            'total_users' => $monthlyCut->userDebts()->count(),
            'paid' => $monthlyCut->userDebts()->paid()->count(),
            'pending' => $monthlyCut->userDebts()->pending()->count(),
            'overdue' => $monthlyCut->userDebts()->overdue()->count(),
            'total_amount' => $monthlyCut->userDebts()->sum('amount'),
            'paid_amount' => $monthlyCut->userDebts()->paid()->sum('amount'),
        ];

        return view('monthly-cuts.show', compact('monthlyCut', 'stats'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(MonthlyCut $monthlyCut)
    {
        return view('monthly-cuts.edit', compact('monthlyCut'));
    }

    /**
     * Actualizar corte mensual
     */
    public function update(Request $request, MonthlyCut $monthlyCut)
    {
        $request->validate([
            'cut_name' => 'required|string|max:255',
            'cut_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $monthlyCut->update($request->only([
            'cut_name', 'cut_date', 'amount', 'description'
        ]));

        return redirect()->route('monthly-cuts.show', $monthlyCut)
                        ->with('success', 'Corte mensual actualizado exitosamente.');
    }

    /**
     * Cerrar un corte mensual
     */
    public function close(MonthlyCut $monthlyCut)
    {
        $monthlyCut->close();

        return redirect()->route('monthly-cuts.index')
                        ->with('success', 'Corte mensual cerrado exitosamente.');
    }

    /**
     * Enviar notificaciones a los usuarios sobre el nuevo corte
     */
    private function notifyUsersAboutNewCut(MonthlyCut $cut)
    {
        $users = User::where('is_active', true)->get();
        
        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new MonthlyCutCreated($cut, $user));
            } catch (\Exception $e) {
                Log::error('Error enviando email de corte mensual: ' . $e->getMessage());
            }
        }
    }
}
