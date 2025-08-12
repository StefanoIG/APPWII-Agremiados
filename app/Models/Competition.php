<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'categoria_id',
        'disciplina_id',
        'members_per_team',
        'max_members',
        'min_members',
        'max_teams',
        'start_date',
        'end_date',
        'registration_deadline',
        'status',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
    ];

    /**
     * Relación con la categoría
     */
    public function categoria()
    {
        return $this->belongsTo(\App\Models\Categoria::class);
    }

    /**
     * Relación con la disciplina
     */
    public function disciplina()
    {
        return $this->belongsTo(\App\Models\Disciplina::class);
    }

    /**
     * Relación con el usuario que creó la competencia
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relación con los equipos de la competencia
     */
    public function teams()
    {
        return $this->hasMany(CompetitionTeam::class);
    }

    /**
     * Relación con los brackets de la competencia
     */
    public function brackets()
    {
        return $this->hasMany(CompetitionBracket::class);
    }

    /**
     * Verificar si la competencia está abierta para inscripciones
     */
    public function isOpenForRegistration()
    {
        return $this->status === 'open' && 
               $this->registration_deadline->copy()->endOfDay() >= Carbon::now() &&
               ($this->max_teams === null || $this->teams()->count() < $this->max_teams);
    }

    /**
     * Verificar si el usuario puede unirse a algún equipo
     */
    public function canUserParticipate($userId)
    {
        return !$this->teams()
            ->whereHas('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->exists();
    }
}
