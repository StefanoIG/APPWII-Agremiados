<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TeamInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'invited_by',
        'type',
        'status',
        'message',
        'expires_at',
        'responded_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Relación con el equipo
     */
    public function team()
    {
        return $this->belongsTo(CompetitionTeam::class, 'team_id');
    }

    /**
     * Relación con el usuario invitado/solicitante
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el usuario que hizo la invitación
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Verificar si la invitación ha expirado
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < Carbon::now();
    }

    /**
     * Verificar si la invitación está pendiente y no ha expirado
     */
    public function isActive()
    {
        return $this->status === 'pending' && !$this->isExpired();
    }
}
