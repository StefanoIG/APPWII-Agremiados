<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'start_date',
        'end_date',
        'status',
        'amount_paid',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount_paid' => 'decimal:2'
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el plan de suscripción
     */
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * Relación con los recibos de pago
     */
    public function paymentReceipts()
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    /**
     * Verificar si la suscripción está activa
     */
    public function isActive()
    {
        return $this->status === 'active' && $this->end_date >= Carbon::today();
    }

    /**
     * Verificar si la suscripción ha expirado
     */
    public function isExpired()
    {
        return $this->end_date < Carbon::today();
    }

    /**
     * Scope para suscripciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('end_date', '>=', Carbon::today());
    }

    /**
     * Scope para suscripciones pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
