<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserDebt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'monthly_cut_id',
        'amount',
        'status',
        'due_date',
        'paid_at',
        'payment_receipt',
        'receipt_url',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el corte mensual
     */
    public function monthlyCut()
    {
        return $this->belongsTo(MonthlyCut::class);
    }

    /**
     * Verificar si la deuda está pendiente
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Verificar si la deuda está pagada
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    /**
     * Verificar si la deuda está vencida
     */
    public function isOverdue()
    {
        return $this->status === 'overdue' || 
               ($this->isPending() && $this->due_date->isPast());
    }

    /**
     * Marcar como pagada
     */
    public function markAsPaid($receiptPath = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_receipt' => $receiptPath
        ]);
    }

    /**
     * Marcar como vencida
     */
    public function markAsOverdue()
    {
        if ($this->isPending() && $this->due_date->isPast()) {
            $this->update(['status' => 'overdue']);
        }
    }

    /**
     * Obtener URL del comprobante de pago
     */
    public function getReceiptUrlAttribute()
    {
        return $this->payment_receipt ? asset('storage/' . $this->payment_receipt) : null;
    }

    /**
     * Scope para deudas pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para deudas pagadas
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope para deudas pendientes de aprobación
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }

    /**
     * Scope para deudas vencidas
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', now());
                    });
    }
}
