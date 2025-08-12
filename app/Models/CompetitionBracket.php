<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompetitionBracket extends Model

{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'team1_id',
        'team2_id',
        'winner_id',
        'round',
        'position',
        'status',
        'match_date',
        'team1_score',
        'team2_score',
        'notes',
        'evidence_file',
        'evidence_type',
        'result_registered_by',
        'result_registered_at'
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'result_registered_at' => 'datetime'
    ];

    /**
     * Relación con la competencia
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Relación con el equipo 1
     */
    public function team1()
    {
        return $this->belongsTo(CompetitionTeam::class, 'team1_id');
    }

    /**
     * Relación con el equipo 2
     */
    public function team2()
    {
        return $this->belongsTo(CompetitionTeam::class, 'team2_id');
    }

    /**
     * Relación con el equipo ganador
     */
    public function winner()
    {
        return $this->belongsTo(CompetitionTeam::class, 'winner_id');
    }

    /**
     * Relación con el usuario que registró el resultado
     */
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'result_registered_by');
    }

    /**
     * Verificar si el partido está completo
     */
    public function isComplete()
    {
        return $this->status === 'completed';
    }

    /**
     * Verificar si el partido está finalizado
     */
    public function isFinished()
    {
        return in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Obtener el nombre del partido
     */
    public function getMatchName()
    {
        if ($this->team1 && $this->team2) {
            return $this->team1->team->name . ' vs ' . $this->team2->team->name;
        } elseif ($this->team1) {
            return $this->team1->team->name . ' (BYE)';
        } elseif ($this->team2) {
            return $this->team2->team->name . ' (BYE)';
        }
        return 'TBD vs TBD';
    }

    /**
     * Obtener el lado del bracket (left, right, final)
     */
    public function getSide()
    {
        return $this->notes;
    }
}
