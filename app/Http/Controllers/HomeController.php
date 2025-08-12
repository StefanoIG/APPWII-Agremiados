<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competition;
use App\Models\CompetitionTeam;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [];
        
        // Métricas para Admin y Secretaria
        if (auth()->user()->hasAnyRole(['admin', 'secretaria'])) {
            // Competencias llenas que necesitan brackets
            $data['competitionsNeedingBrackets'] = Competition::whereHas('teams', function($query) {
                $query->havingRaw('COUNT(*) >= competitions.max_teams');
            })->where('status', 'registration_closed')
              ->whereDoesntHave('brackets')
              ->count();
            
            // Próxima competencia más cercana
            $nextCompetition = Competition::where('start_date', '>', now())
                ->orderBy('start_date', 'asc')
                ->first();
            
            if ($nextCompetition) {
                $days = now()->diffInDays($nextCompetition->start_date, false);
                // Si tiene decimales (horas), redondear hacia arriba
                $hours = now()->diffInHours($nextCompetition->start_date, false);
                $data['nextCompetitionDays'] = $days > 0 ? ceil($days) : ($hours > 0 ? 1 : 0);
                $data['nextCompetitionName'] = $nextCompetition->name;
            } else {
                $data['nextCompetitionDays'] = null;
                $data['nextCompetitionName'] = null;
            }
            
            // Competencias que se llenan pronto
            $data['competitionsNearFull'] = Competition::whereRaw('(
                SELECT COUNT(*) FROM competition_teams WHERE competition_id = competitions.id
            ) >= (max_teams * 0.8)')
            ->where('status', 'registration_open')
            ->count();
            
            // Usuarios pendientes de aprobación
            $data['pendingUsers'] = User::where('is_active', false)->count();
        }
        
        // Métricas específicas para usuarios
        if (auth()->user()->hasRole('user')) {
            // Equipos del usuario
            $data['userTeams'] = CompetitionTeam::whereHas('members', function($query) {
                $query->where('user_id', auth()->id())->where('status', 'active');
            })->count();
            
            // Competencias disponibles para unirse
            $data['availableCompetitions'] = Competition::where('status', 'registration_open')
                ->where('registration_deadline', '>', now())
                ->count();
        }
        
        return view('home', $data);
    }
}
