<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_subscription_id',
        'user_id',
        'receipt_image',
        'amount',
        'payment_date',
        'payment_method',
        'user_notes',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'reviewed_at' => 'datetime'
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la suscripción
     */
    public function userSubscription()
    {
        return $this->belongsTo(UserSubscription::class);
    }

    /**
     * Relación con el usuario que revisó el pago
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope para recibos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para recibos aprobados
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope para recibos rechazados
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Obtener la URL del archivo de imagen
     */
    public function getReceiptUrlAttribute()
    {
        return $this->receipt_image ? asset('storage/' . $this->receipt_image) : null;
    }

    /**
     * Accessor para receipt_path que mapea a receipt_image
     */
    public function getReceiptPathAttribute()
    {
        return $this->receipt_image;
    }

    /**
     * Mutator para receipt_path que mapea a receipt_image
     */
    public function setReceiptPathAttribute($value)
    {
        $this->attributes['receipt_image'] = $value;
    }
}
