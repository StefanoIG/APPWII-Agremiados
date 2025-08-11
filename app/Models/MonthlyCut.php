<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MonthlyCut extends Model
{
    use HasFactory;

    protected $fillable = [
        'cut_name',
        'cut_date',
        'amount',
        'description',
        'status',
        'created_by'
    ];

    protected $casts = [
        'cut_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Relación con el usuario que creó el corte (secretaria)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relación con las deudas generadas por este corte
     */
    public function userDebts()
    {
        return $this->hasMany(UserDebt::class);
    }

    /**
     * Generar deudas para todos los usuarios activos
     */
    public function generateDebtsForAllUsers()
    {
        $activeUsers = User::where('is_active', true)->get();
        
        foreach ($activeUsers as $user) {
            // Solo crear deuda si no existe ya para este usuario y corte
            UserDebt::firstOrCreate([
                'user_id' => $user->id,
                'monthly_cut_id' => $this->id,
            ], [
                'amount' => $this->amount,
                'due_date' => $this->cut_date->addDays(30), // 30 días para pagar
                'status' => 'pending'
            ]);
        }
    }

    /**
     * Verificar si el corte está activo
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Cerrar el corte
     */
    public function close()
    {
        $this->update(['status' => 'closed']);
    }
}
