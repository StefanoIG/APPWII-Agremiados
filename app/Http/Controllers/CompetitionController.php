<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionTeam;
use App\Models\Categoria;
use App\Models\Disciplina;
use App\Jobs\SendCompetitionNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompetitionController extends Controller
{
    /**
     * Mostrar todas las competencias
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasAnyRole('admin', 'secretaria')) {
            // Admin y secretaria ven todas las competencias
            $competitions = Competition::with(['categoria', 'disciplina', 'creator'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Usuarios ven solo competencias abiertas
            $competitions = Competition::with(['categoria', 'disciplina'])
                ->where('status', 'open')
                ->where('registration_deadline', '>=', now())
                ->orderBy('start_date')
                ->paginate(10);
        }
        
        return view('competitions.index', compact('competitions'));
    }

    /**
     * Mostrar formulario para crear competencia (solo admin/secretaria)
     */
    public function create()
    {
        $categorias = Categoria::where('is_active', true)->get();
        $disciplinas = Disciplina::where('is_active', true)->get();
        
        return view('competitions.create', compact('categorias', 'disciplinas'));
    }

    /**
     * Guardar nueva competencia
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'members_per_team' => 'required|integer|min:1',
            'max_members' => 'required|integer|min:1',
            'min_members' => 'required|integer|min:1',
            'max_teams' => 'nullable|integer|min:1',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|after:today|before:start_date',
        ]);

        $user = Auth::user();
        
        // Determinar el estado inicial basado en el rol del usuario
        $initialStatus = 'draft'; // Por defecto draft
        if ($user->hasRole('admin')) {
            $initialStatus = 'open'; // Admin puede crear directamente en estado open
        }

        $competition = Competition::create([
            'name' => $request->name,
            'description' => $request->description,
            'categoria_id' => $request->categoria_id,
            'disciplina_id' => $request->disciplina_id,
            'members_per_team' => $request->members_per_team,
            'max_members' => $request->max_members,
            'min_members' => $request->min_members,
            'max_teams' => $request->max_teams,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'registration_deadline' => $request->registration_deadline,
            'status' => $initialStatus,
            'created_by' => Auth::id(),
        ]);

        // Solo enviar notificaciones si está aprobada (open)
        if ($initialStatus === 'open') {
            SendCompetitionNotifications::dispatch($competition);
            $message = 'Competencia creada y publicada exitosamente. Se han enviado notificaciones a todos los usuarios con suscripciones activas.';
        } else {
            $message = 'Competencia creada exitosamente. Está pendiente de aprobación por un administrador.';
        }

        return redirect()->route('competitions.index')
            ->with('success', $message);
    }

    /**
     * Mostrar detalles de una competencia
     */
    public function show(Competition $competition)
    {
        $competition->load(['categoria', 'disciplina', 'teams.captain', 'creator']);
        
        $userTeam = null;
        if (Auth::check()) {
            $userTeam = $competition->teams()
                ->whereHas('members', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->first();
        }
        
        return view('competitions.show', compact('competition', 'userTeam'));
    }

    /**
     * Mostrar equipos del usuario autenticado
     */
    public function userTeams()
    {
        $user = Auth::user();
        
        $teams = CompetitionTeam::whereHas('members', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['competition', 'members.user'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('competitions.user-teams', compact('teams'));
    }

    /**
     * Formulario para editar competencia
     */
    public function edit(Competition $competition)
    {
        $categorias = Categoria::where('is_active', true)->get();
        $disciplinas = Disciplina::where('is_active', true)->get();
        
        return view('competitions.edit', compact('competition', 'categorias', 'disciplinas'));
    }

    /**
     * Actualizar competencia
     */
    public function update(Request $request, Competition $competition)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'members_per_team' => 'required|integer|min:1',
            'max_members' => 'required|integer|min:1',
            'min_members' => 'required|integer|min:1',
            'max_teams' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:start_date',
            'status' => 'required|in:draft,open,closed,in_progress,finished',
        ]);

        $competition->update($request->all());

        return redirect()->route('competitions.show', $competition)
            ->with('success', 'Competencia actualizada exitosamente.');
    }

    /**
     * Aprobar una competencia (solo admin)
     */
    public function approve(Competition $competition)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        if ($competition->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Solo se pueden aprobar competencias en estado borrador.');
        }

        $competition->update([
            'status' => 'open',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Enviar notificaciones a usuarios con suscripciones activas
        SendCompetitionNotifications::dispatch($competition);

        return redirect()->back()
            ->with('success', 'Competencia aprobada exitosamente. Se han enviado notificaciones a todos los usuarios.');
    }

    /**
     * Rechazar una competencia (solo admin)
     */
    public function reject(Competition $competition)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        if ($competition->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Solo se pueden rechazar competencias en estado borrador.');
        }

        $competition->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Competencia rechazada.');
    }
}
