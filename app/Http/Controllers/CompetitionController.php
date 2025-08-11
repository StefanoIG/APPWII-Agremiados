<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionTeam;
use App\Models\CompetitionTeamMember;
use App\Models\CompetitionBracket;
use App\Models\TeamInvitation;
use App\Models\User;
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
        $isCaptain = false;
        $availableUsers = collect();
        
        if (Auth::check()) {
            $userTeam = $competition->teams()
                ->whereHas('members', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->first();
                
            if ($userTeam) {
                $isCaptain = $userTeam->captain_id === Auth::id();
                
                // Si es capitán, obtener usuarios disponibles para invitar
                if ($isCaptain && $userTeam->current_members < $competition->max_members) {
                    $availableUsers = User::where('is_active', true)
                        ->whereDoesntHave('teamMemberships', function($query) use ($competition) {
                            $query->whereHas('team', function($subQuery) use ($competition) {
                                $subQuery->where('competition_id', $competition->id);
                            });
                        })
                        ->whereDoesntHave('teamInvitations', function($query) use ($userTeam) {
                            $query->where('team_id', $userTeam->id)
                                  ->where('status', 'pending');
                        })
                        ->where('id', '!=', Auth::id())
                        ->get();
                }
            }
        }
        
        return view('competitions.show', compact('competition', 'userTeam', 'isCaptain', 'availableUsers'));
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

    /**
     * Crear un nuevo equipo en una competencia
     */
    public function createTeam(Request $request, Competition $competition)
    {
        // Verificar que el usuario puede participar
        if (!Auth::user()->canParticipateInCompetitions()) {
            return redirect()->back()
                ->with('error', 'Necesitas una suscripción activa para participar en competencias.');
        }

        // Verificar que la competencia está abierta para inscripciones
        if (!$competition->isOpenForRegistration()) {
            return redirect()->back()
                ->with('error', 'Las inscripciones para esta competencia están cerradas.');
        }

        // Verificar que el usuario no está ya en un equipo de esta competencia
        $existingTeam = $competition->teams()
            ->whereHas('members', function($query) {
                $query->where('user_id', Auth::id());
            })->first();

        if ($existingTeam) {
            return redirect()->back()
                ->with('error', 'Ya estás participando en un equipo de esta competencia.');
        }

        $request->validate([
            'team_name' => 'required|string|max:255',
            'team_description' => 'nullable|string|max:1000',
            'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Manejar la subida del logo
        $logoPath = null;
        if ($request->hasFile('team_logo')) {
            $logoPath = $request->file('team_logo')->store('team_logos', 'public');
        }

        // Crear el equipo
        $team = CompetitionTeam::create([
            'competition_id' => $competition->id,
            'name' => $request->team_name,
            'description' => $request->team_description,
            'logo' => $logoPath,
            'captain_id' => Auth::id(),
            'status' => 'active',
            'current_members' => 1,
        ]);

        // Agregar al usuario como capitán del equipo
        $team->members()->create([
            'user_id' => Auth::id(),
            'is_captain' => true,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Equipo creado exitosamente. Ahora eres el capitán del equipo "' . $team->name . '".');
    }

    /**
     * Unirse a un equipo existente
     */
    public function joinTeam(Request $request, Competition $competition, CompetitionTeam $team)
    {
        // Verificar que el usuario puede participar
        if (!Auth::user()->canParticipateInCompetitions()) {
            return redirect()->back()
                ->with('error', 'Necesitas una suscripción activa para participar en competencias.');
        }

        // Verificar que la competencia está abierta para inscripciones
        if (!$competition->isOpenForRegistration()) {
            return redirect()->back()
                ->with('error', 'Las inscripciones para esta competencia están cerradas.');
        }

        // Verificar que el equipo pertenece a esta competencia
        if ($team->competition_id !== $competition->id) {
            return redirect()->back()
                ->with('error', 'El equipo no pertenece a esta competencia.');
        }

        // Verificar que el usuario no está ya en un equipo de esta competencia
        $existingTeam = $competition->teams()
            ->whereHas('members', function($query) {
                $query->where('user_id', Auth::id());
            })->first();

        if ($existingTeam) {
            return redirect()->back()
                ->with('error', 'Ya estás participando en un equipo de esta competencia.');
        }

        // Verificar que el equipo no está lleno
        if ($team->current_members >= $competition->max_members) {
            return redirect()->back()
                ->with('error', 'El equipo ya está completo.');
        }

        // Agregar al usuario al equipo
        $team->members()->create([
            'user_id' => Auth::id(),
            'is_captain' => false,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // Actualizar el contador de miembros
        $team->increment('current_members');

        return redirect()->back()
            ->with('success', 'Te has unido exitosamente al equipo "' . $team->name . '".');
    }

    /**
     * Salir de un equipo
     */
    public function leaveTeam(Request $request, Competition $competition, CompetitionTeam $team)
    {
        // Verificar que el equipo pertenece a esta competencia
        if ($team->competition_id !== $competition->id) {
            return redirect()->back()
                ->with('error', 'El equipo no pertenece a esta competencia.');
        }

        // Buscar la membresía del usuario
        $membership = $team->members()->where('user_id', Auth::id())->first();

        if (!$membership) {
            return redirect()->back()
                ->with('error', 'No eres miembro de este equipo.');
        }

        // Si es el capitán y hay otros miembros, no puede salir
        if ($membership->is_captain && $team->current_members > 1) {
            return redirect()->back()
                ->with('error', 'Como capitán, debes transferir el liderazgo antes de salir del equipo.');
        }

        // Eliminar la membresía
        $membership->delete();

        // Actualizar el contador de miembros
        $team->decrement('current_members');

        // Si era el último miembro, eliminar el equipo
        if ($team->current_members == 0) {
            $team->delete();
            $message = 'Has salido del equipo y como eras el último miembro, el equipo ha sido eliminado.';
        } else {
            $message = 'Has salido exitosamente del equipo "' . $team->name . '".';
        }

        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Invitar un jugador al equipo
     */
    public function invitePlayer(Request $request, Competition $competition, CompetitionTeam $team)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:500',
        ]);

        // Verificar que el usuario autenticado es el capitán del equipo
        if ($team->captain_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'Solo el capitán del equipo puede enviar invitaciones.');
        }

        // Verificar que el equipo no está lleno
        if ($team->current_members >= $competition->max_members) {
            return redirect()->back()
                ->with('error', 'El equipo ya está completo.');
        }

        $userId = $request->user_id;

        // Verificar que el usuario puede participar en competencias
        $user = User::find($userId);
        if (!$user->canParticipateInCompetitions()) {
            return redirect()->back()
                ->with('error', 'El usuario seleccionado no tiene suscripción activa.');
        }

        // Verificar que el usuario no está ya en un equipo de esta competencia
        $existingTeam = $competition->teams()
            ->whereHas('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();

        if ($existingTeam) {
            return redirect()->back()
                ->with('error', 'El usuario ya está participando en un equipo de esta competencia.');
        }

        // Verificar que no hay una invitación pendiente
        $existingInvitation = TeamInvitation::where('team_id', $team->id)
            ->where('user_id', $userId)
            ->where('type', 'invitation')
            ->where('status', 'pending')
            ->first();

        if ($existingInvitation) {
            return redirect()->back()
                ->with('error', 'Ya hay una invitación pendiente para este usuario.');
        }

        // Crear la invitación
        TeamInvitation::create([
            'team_id' => $team->id,
            'user_id' => $userId,
            'invited_by' => Auth::id(),
            'type' => 'invitation',
            'status' => 'pending',
            'message' => $request->message,
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()->back()
            ->with('success', 'Invitación enviada exitosamente a ' . $user->name . '.');
    }

    /**
     * Aceptar una invitación
     */
    public function acceptInvitation(TeamInvitation $invitation)
    {
        if ($invitation->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        if ($invitation->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Esta invitación ya ha sido procesada.');
        }

        if ($invitation->isExpired()) {
            return redirect()->back()
                ->with('error', 'Esta invitación ha expirado.');
        }

        $team = $invitation->team;
        $competition = $team->competition;

        // Verificar que el usuario puede participar
        if (!Auth::user()->canParticipateInCompetitions()) {
            return redirect()->back()
                ->with('error', 'Necesitas una suscripción activa para participar en competencias.');
        }

        // Verificar que la competencia está abierta
        if (!$competition->isOpenForRegistration()) {
            return redirect()->back()
                ->with('error', 'Las inscripciones para esta competencia están cerradas.');
        }

        // Verificar que el equipo no está lleno
        if ($team->current_members >= $competition->max_members) {
            return redirect()->back()
                ->with('error', 'El equipo ya está completo.');
        }

        // Verificar que no está en otro equipo de la misma competencia
        $existingTeam = $competition->teams()
            ->whereHas('members', function($query) {
                $query->where('user_id', Auth::id());
            })->first();

        if ($existingTeam) {
            return redirect()->back()
                ->with('error', 'Ya estás participando en un equipo de esta competencia.');
        }

        // Agregar al usuario al equipo
        $team->members()->create([
            'user_id' => Auth::id(),
            'is_captain' => false,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // Actualizar el contador de miembros
        $team->increment('current_members');

        // Marcar la invitación como aceptada
        $invitation->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        return redirect()->route('competitions.show', $competition)
            ->with('success', 'Te has unido exitosamente al equipo "' . $team->name . '".');
    }

    /**
     * Rechazar una invitación
     */
    public function rejectInvitation(TeamInvitation $invitation)
    {
        if ($invitation->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        if ($invitation->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Esta invitación ya ha sido procesada.');
        }

        $invitation->update([
            'status' => 'rejected',
            'responded_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Invitación rechazada.');
    }

    /**
     * Ver las invitaciones del usuario
     */
    public function myInvitations()
    {
        $invitations = TeamInvitation::where('user_id', Auth::id())
            ->with(['team.competition', 'inviter'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('competitions.invitations', compact('invitations'));
    }

    /**
     * Generar brackets para la competencia
     */
    public function generateBrackets(Competition $competition)
    {
        // Verificar permisos
        if (!Auth::user()->hasRole(['admin', 'secretaria'])) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // Obtener equipos de la competencia
        $teams = $competition->teams()->where('status', 'active')->get();
        
        if ($teams->count() < 2) {
            return redirect()->back()
                ->with('error', 'Se necesitan al menos 2 equipos para generar brackets.');
        }

        // Limpiar brackets existentes
        CompetitionBracket::where('competition_id', $competition->id)->delete();

        // Barajear equipos aleatoriamente
        $shuffledTeams = $teams->shuffle();
        $teamCount = $shuffledTeams->count();
        
        // Dividir equipos en dos grupos (izquierda y derecha)
        $leftTeams = $shuffledTeams->take(ceil($teamCount / 2));
        $rightTeams = $shuffledTeams->skip(ceil($teamCount / 2));
        
        $this->generateSideBrackets($competition->id, $leftTeams, 'left');
        $this->generateSideBrackets($competition->id, $rightTeams, 'right');
        
        // Generar la final (lado izquierdo vs lado derecho)
        $totalRoundsLeft = $this->calculateRounds($leftTeams->count());
        $totalRoundsRight = $this->calculateRounds($rightTeams->count());
        $finalRound = max($totalRoundsLeft, $totalRoundsRight) + 1;
        
        CompetitionBracket::create([
            'competition_id' => $competition->id,
            'round' => $finalRound,
            'position' => 1,
            'status' => 'pending',
            'notes' => 'final'
        ]);

        return redirect()->route('competitions.brackets', $competition)
            ->with('success', 'Brackets generados exitosamente.');
    }

    /**
     * Generar brackets para un lado (izquierdo o derecho)
     */
    private function generateSideBrackets($competitionId, $teams, $side)
    {
        $teamArray = $teams->values()->toArray();
        $teamCount = count($teamArray);
        
        if ($teamCount <= 1) {
            // Si solo hay un equipo o ninguno, no generamos brackets
            return;
        }
        
        // Solo generar la primera ronda con equipos reales
        $position = 1;
        $teamsToMatch = $teamArray;
        
        // Si es número impar, uno pasa automáticamente (bye)
        $byeTeam = null;
        if (count($teamsToMatch) % 2 != 0) {
            $byeTeam = array_pop($teamsToMatch);
        }
        
        // Crear partidos para la primera ronda
        for ($i = 0; $i < count($teamsToMatch); $i += 2) {
            $team1 = $teamsToMatch[$i];
            $team2 = $teamsToMatch[$i + 1];
            
            CompetitionBracket::create([
                'competition_id' => $competitionId,
                'team1_id' => $team1['id'],
                'team2_id' => $team2['id'],
                'round' => 1,
                'position' => $position,
                'status' => 'pending',
                'notes' => $side
            ]);
            
            $position++;
        }
        
        // Si hay un equipo con bye, crear un "partido" especial
        if ($byeTeam) {
            CompetitionBracket::create([
                'competition_id' => $competitionId,
                'team1_id' => $byeTeam['id'],
                'team2_id' => null,
                'round' => 1,
                'position' => $position,
                'status' => 'completed',
                'winner_id' => $byeTeam['id'],
                'notes' => $side
            ]);
        }
        
        // Generar rondas subsiguientes vacías (para estructura visual)
        $this->generateEmptyRounds($competitionId, $side, $teamCount);
    }

    /**
     * Generar rondas vacías para la estructura visual
     */
    private function generateEmptyRounds($competitionId, $side, $initialTeamCount)
    {
        $totalRounds = $this->calculateRounds($initialTeamCount);
        
        for ($round = 2; $round <= $totalRounds; $round++) {
            $matchesInRound = ceil($initialTeamCount / pow(2, $round));
            
            // Solo crear si hay al menos un partido necesario
            if ($matchesInRound >= 1) {
                for ($position = 1; $position <= $matchesInRound; $position++) {
                    CompetitionBracket::create([
                        'competition_id' => $competitionId,
                        'team1_id' => null,
                        'team2_id' => null,
                        'round' => $round,
                        'position' => $position,
                        'status' => 'pending',
                        'notes' => $side
                    ]);
                }
            }
        }
    }

    /**
     * Calcular número de rondas necesarias para un número de equipos
     */
    private function calculateRounds($teamCount)
    {
        if ($teamCount <= 1) return 0;
        
        $rounds = 0;
        $teams = $teamCount;
        
        while ($teams > 1) {
            $teams = ceil($teams / 2);
            $rounds++;
        }
        
        return $rounds;
    }

    /**
     * Mostrar brackets de la competencia
     */
    public function showBrackets(Competition $competition)
    {
        $brackets = CompetitionBracket::where('competition_id', $competition->id)
            ->with(['team1', 'team2', 'winner'])
            ->orderBy('round')
            ->orderBy('position')
            ->get();

        // Organizar brackets por lado y ronda
        $leftBrackets = $brackets->where('notes', 'left')->groupBy('round');
        $rightBrackets = $brackets->where('notes', 'right')->groupBy('round');
        $finalMatch = $brackets->where('notes', 'final')->first();

        // Calcular el número máximo de rondas
        $maxRoundsLeft = $leftBrackets->keys()->max() ?? 0;
        $maxRoundsRight = $rightBrackets->keys()->max() ?? 0;
        $maxRounds = max($maxRoundsLeft, $maxRoundsRight);

        return view('competitions.brackets', compact(
            'competition', 
            'leftBrackets', 
            'rightBrackets', 
            'finalMatch',
            'maxRounds'
        ));
    }

    /**
     * Obtener la siguiente potencia de 2
     */
    private function getNextPowerOfTwo($number)
    {
        return pow(2, ceil(log($number, 2)));
    }

    /**
     * Mostrar formulario para registrar resultado de un partido
     */
    public function showMatchResult(Competition $competition, CompetitionBracket $bracket)
    {
        // Verificar permisos
        if (!Auth::user()->hasAnyRole('admin', 'secretaria')) {
            abort(403, 'No tienes permisos para registrar resultados.');
        }

        $bracket->load(['team1', 'team2', 'registeredBy']);
        
        return view('competitions.match-result', compact('competition', 'bracket'));
    }

    /**
     * Registrar el resultado de un partido
     */
    public function storeMatchResult(Request $request, Competition $competition, CompetitionBracket $bracket)
    {
        // Verificar permisos
        if (!Auth::user()->hasAnyRole('admin', 'secretaria')) {
            abort(403, 'No tienes permisos para registrar resultados.');
        }

        $request->validate([
            'team1_score' => 'required|integer|min:0',
            'team2_score' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
            'evidence' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Validar que los puntajes no sean iguales
        if ($request->team1_score == $request->team2_score) {
            return back()->withErrors(['error' => 'No puede haber empates. Debe haber un ganador.']);
        }

        // Determinar el ganador
        $winnerId = $request->team1_score > $request->team2_score ? 
                   $bracket->team1_id : $bracket->team2_id;

        // Subir evidencia si se proporciona
        $evidenceFile = null;
        $evidenceType = null;
        
        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $evidenceFile = $file->storeAs('competition_evidence', $fileName, 'public');
            $evidenceType = $file->getClientOriginalExtension();
        }

        // Actualizar el bracket con el resultado
        $bracket->update([
            'team1_score' => $request->team1_score,
            'team2_score' => $request->team2_score,
            'winner_id' => $winnerId,
            'notes' => $request->notes,
            'evidence_file' => $evidenceFile,
            'evidence_type' => $evidenceType,
            'status' => 'completed',
            'result_registered_by' => Auth::id(),
            'result_registered_at' => now()
        ]);

        // Avanzar al ganador en el siguiente round si existe
        $this->advanceWinnerToNextRound($competition, $bracket, $winnerId);

        return redirect()
            ->route('competitions.brackets', $competition)
            ->with('success', 'Resultado registrado exitosamente.');
    }

    /**
     * Avanzar ganador al siguiente round
     */
    private function advanceWinnerToNextRound(Competition $competition, CompetitionBracket $bracket, $winnerId)
    {
        $nextRound = $bracket->round + 1;
        
        // Calcular la posición en el siguiente round
        $nextPosition = ceil($bracket->position / 2);
        
        // Buscar el bracket del siguiente round
        $nextBracket = CompetitionBracket::where('competition_id', $competition->id)
            ->where('round', $nextRound)
            ->where('position', $nextPosition)
            ->first();

        if ($nextBracket) {
            // Determinar si el ganador va como team1 o team2
            if ($bracket->position % 2 == 1) {
                // Posición impar -> va como team1
                $nextBracket->update(['team1_id' => $winnerId]);
            } else {
                // Posición par -> va como team2
                $nextBracket->update(['team2_id' => $winnerId]);
            }
        }
    }

    /**
     * Descargar evidencia de un partido
     */
    public function downloadEvidence(Competition $competition, CompetitionBracket $bracket)
    {
        if (!$bracket->evidence_file) {
            abort(404, 'No hay evidencia disponible para este partido.');
        }

        $path = storage_path('app/public/' . $bracket->evidence_file);
        
        if (!file_exists($path)) {
            abort(404, 'Archivo de evidencia no encontrado.');
        }

        return response()->download($path);
    }
}
