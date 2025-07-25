<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'competition_id',
        'captain_id',
        'status',
        'current_members'
    ];

    /**
     * Relación con la competencia
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Relación con el capitán del equipo
     */
    public function captain()
    {
        return $this->belongsTo(User::class, 'captain_id');
    }

    /**
     * Relación con los miembros del equipo
     */
    public function members()
    {
        return $this->hasMany(CompetitionTeamMember::class, 'team_id');
    }

    /**
     * Relación con los usuarios miembros
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'competition_team_members', 'team_id', 'user_id')
                    ->withPivot('is_captain', 'status', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Relación con las invitaciones del equipo
     */
    public function invitations()
    {
        return $this->hasMany(TeamInvitation::class, 'team_id');
    }

    /**
     * Verificar si el equipo puede aceptar más miembros
     */
    public function canAcceptMoreMembers()
    {
        return $this->current_members < $this->competition->max_members;
    }

    /**
     * Verificar si el equipo tiene el mínimo de miembros
     */
    public function hasMinimumMembers()
    {
        return $this->current_members >= $this->competition->min_members;
    }

    /**
     * Verificar si un usuario es miembro del equipo
     */
    public function hasMember($userId)
    {
        return $this->members()->where('user_id', $userId)->where('status', 'active')->exists();
    }
}
