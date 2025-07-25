<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionTeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'is_captain',
        'status',
        'joined_at'
    ];

    protected $casts = [
        'is_captain' => 'boolean',
        'joined_at' => 'datetime',
    ];

    /**
     * Relación con el equipo
     */
    public function team()
    {
        return $this->belongsTo(CompetitionTeam::class, 'team_id');
    }

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
