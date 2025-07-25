@extends('adminlte::page')

@section('title', $competition->name)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $competition->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('competitions.index') }}">Competencias</a></li>
                    <li class="breadcrumb-item active">{{ $competition->name }}</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Información de la Competencia -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información de la Competencia</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ 
                            $competition->status === 'open' ? 'success' : 
                            ($competition->status === 'closed' ? 'warning' : 
                            ($competition->status === 'in_progress' ? 'info' : 'secondary')) 
                        }} badge-lg">
                            {{ ucfirst($competition->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($competition->description)
                        <p class="lead">{{ $competition->description }}</p>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Detalles de la Competencia</h5>
                            <p><strong>Disciplina:</strong> {{ $competition->disciplina->name ?? 'N/A' }}</p>
                            <p><strong>Categoría:</strong> {{ $competition->categoria->name ?? 'N/A' }}</p>
                            <p><strong>Miembros por equipo:</strong> {{ $competition->members_per_team }}</p>
                            <p><strong>Mínimo de miembros:</strong> {{ $competition->min_members }}</p>
                            <p><strong>Máximo de miembros:</strong> {{ $competition->max_members }}</p>
                            @if($competition->max_teams)
                                <p><strong>Máximo de equipos:</strong> {{ $competition->max_teams }}</p>
                            @endif
                            <p><strong>Creado por:</strong> 
                            @if($competition->creator)
                                {{ $competition->creator->name }}
                            @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Fechas Importantes</h5>
                            <p><strong>Fecha de inicio:</strong> {{ $competition->start_date->format('d/m/Y') }}</p>
                            <p><strong>Fecha de fin:</strong> {{ $competition->end_date->format('d/m/Y') }}</p>
                            <p><strong>Límite de inscripción:</strong> {{ $competition->registration_deadline->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
                
                @if(auth()->user()->hasRole(['admin', 'secretaria']))
                    <div class="card-footer">
                        <a href="{{ route('competitions.edit', $competition) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Competencia
                        </a>
                        <a href="{{ route('competitions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a Competencias
                        </a>
                    </div>
                @elseif(auth()->user()->hasRole('admin'))
                    <div class="card-footer">
                        <a href="{{ route('competitions.edit', $competition) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Competencia
                        </a>
                        <a href="{{ route('competitions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a Competencias
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Panel de Acciones del Usuario -->
        <div class="col-md-4">
            @if($competition->isOpenForRegistration())
                <div class="card border-success">
                    <div class="card-header bg-success">
                        <h4 class="card-title text-white mb-0">
                            <i class="fas fa-users"></i> Participar
                        </h4>
                    </div>
                    <div class="card-body">
                        @if(isset($userTeam) && $userTeam)
                            <div class="alert alert-info">
                                <i class="fas fa-check"></i> Ya estás participando en el equipo: 
                                <strong>{{ $userTeam->name }}</strong>
                                @if(isset($isCaptain) && $isCaptain)
                                    <br><small class="text-muted"><i class="fas fa-crown"></i> Eres el capitán</small>
                                @endif
                            </div>
                            <a href="{{ route('competitions.teams') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-eye"></i> Ver Mi Equipo
                            </a>
                            @if(isset($isCaptain) && $isCaptain && $userTeam->current_members < $competition->max_members && isset($availableUsers) && $availableUsers->count() > 0)
                                <button class="btn btn-success btn-block" data-toggle="modal" data-target="#invitePlayerModal">
                                    <i class="fas fa-user-plus"></i> Invitar Jugador
                                </button>
                            @endif
                        @elseif(!auth()->user()->hasRole(['admin', 'secretaria']) && !auth()->user()->canParticipateInCompetitions())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Suscripción Requerida</strong><br>
                                Para participar en competencias necesitas tener una suscripción activa.
                            </div>
                            <a href="{{ route('subscriptions.plans') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-star"></i> Ver Planes de Suscripción
                            </a>
                        @else
                            <p class="text-center">¿Quieres participar en esta competencia?</p>
                            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#createTeamModal">
                                <i class="fas fa-plus"></i> Crear Equipo
                            </button>
                            <button class="btn btn-outline-success btn-block" data-toggle="modal" data-target="#joinTeamModal">
                                <i class="fas fa-search"></i> Buscar Equipo
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="card border-warning">
                    <div class="card-header bg-warning">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Inscripciones Cerradas
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-center">Las inscripciones para esta competencia han cerrado.</p>
                    </div>
                </div>
            @endif

            <!-- Estadísticas -->
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title"><i class="fas fa-chart-bar"></i> Estadísticas</h4>
                </div>
                <div class="card-body">
                    <p><strong>Equipos inscritos:</strong> {{ $competition->teams->count() }}
                        @if($competition->max_teams)
                            / {{ $competition->max_teams }}
                        @endif
                    </p>
                    <p><strong>Total de participantes:</strong> {{ $competition->teams->sum('current_members') }}</p>
                    
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $competition->max_teams ? ($competition->teams->count() / $competition->max_teams) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Equipos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> Equipos Participantes</h3>
                </div>
                <div class="card-body">
                    @if($competition->teams->count() > 0)
                        <div class="row">
                            @foreach($competition->teams as $team)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-left-primary">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <img src="{{ $team->getLogoUrl() }}" 
                                                     alt="Logo de {{ $team->name }}" 
                                                     class="img-fluid rounded-circle" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            </div>
                                            <h5 class="card-title">{{ $team->name }}</h5>
                                            <p class="card-text">
                                                <strong>Capitán:</strong> {{ $team->captain->name }}<br>
                                                <strong>Miembros:</strong> {{ $team->current_members }} / {{ $competition->max_members }}
                                                @if($team->description)
                                                    <br><small class="text-muted">{{ Str::limit($team->description, 50) }}</small>
                                                @endif
                                            </p>
                                            <span class="badge badge-{{ $team->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($team->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aún no hay equipos inscritos en esta competencia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear Equipo -->
@if($competition->isOpenForRegistration() && (!isset($userTeam) || !$userTeam))
<div class="modal fade" id="createTeamModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Nuevo Equipo</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('competitions.teams.create', $competition) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="team_name">Nombre del Equipo *</label>
                        <input type="text" class="form-control" id="team_name" name="team_name" required>
                    </div>
                    <div class="form-group">
                        <label for="team_description">Descripción (opcional)</label>
                        <textarea class="form-control" id="team_description" name="team_description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="team_logo">Logo del Equipo (opcional)</label>
                        <input type="file" class="form-control-file" id="team_logo" name="team_logo" accept="image/*">
                        <small class="form-text text-muted">Formatos soportados: JPEG, PNG, JPG, GIF. Tamaño máximo: 2MB</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Te convertirás automáticamente en el capitán del equipo.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Equipo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Modal para Buscar Equipo -->
@if($competition->isOpenForRegistration() && (!isset($userTeam) || !$userTeam))
<div class="modal fade" id="joinTeamModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buscar Equipo para Unirse</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Aquí puedes ver todos los equipos disponibles y solicitar unirte a uno:</p>
                
                @if($competition->teams->where('current_members', '<', $competition->max_members)->count() > 0)
                    <div class="row">
                        @foreach($competition->teams->where('current_members', '<', $competition->max_members) as $team)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img src="{{ $team->getLogoUrl() }}" 
                                                 alt="Logo de {{ $team->name }}" 
                                                 class="img-fluid rounded-circle" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        </div>
                                        <h6 class="card-title">{{ $team->name }}</h6>
                                        <p class="card-text small">
                                            <strong>Capitán:</strong> {{ $team->captain->name }}<br>
                                            <strong>Miembros:</strong> {{ $team->current_members }} / {{ $competition->max_members }}<br>
                                            <strong>Espacios libres:</strong> {{ $competition->max_members - $team->current_members }}
                                            @if($team->description)
                                                <br><span class="text-muted">{{ Str::limit($team->description, 60) }}</span>
                                            @endif
                                        </p>
                                        <form action="{{ route('competitions.teams.join', [$competition, $team]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus"></i> Unirse
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> 
                        No hay equipos disponibles con espacios libres en este momento.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal para Invitar Jugador -->
@if(isset($userTeam) && isset($isCaptain) && $userTeam && $isCaptain && $userTeam->current_members < $competition->max_members)
<div class="modal fade" id="invitePlayerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invitar Jugador al Equipo</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('competitions.teams.invite', [$competition, $userTeam]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_id">Seleccionar Jugador *</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="">Selecciona un jugador...</option>
                            @if(isset($availableUsers))
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            @endif
                        </select>
                        <small class="form-text text-muted">Solo se muestran usuarios con suscripción activa que no estén en otro equipo.</small>
                    </div>
                    <div class="form-group">
                        <label for="message">Mensaje (opcional)</label>
                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="Escribe un mensaje personalizado para la invitación..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> El jugador recibirá una invitación que podrá aceptar o rechazar.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Invitación</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@stop

@section('css')
<style>
/* Estilos para los modales */
.modal {
    z-index: 1055;
}

.modal-backdrop {
    z-index: 1050;
}

/* Prevenir que los modales se muevan */
.modal.show .modal-dialog {
    transform: none !important;
}

.modal-dialog {
    margin: 1.75rem auto;
    max-width: 500px;
}

.modal-lg {
    max-width: 800px;
}

/* Estilos para las tarjetas de equipos */
.border-left-primary {
    border-left: 0.25rem solid #007bff !important;
}

/* Mejoras visuales */
.card-body img {
    border: 2px solid #dee2e6;
}

.progress {
    height: 10px;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

/* Prevenir salto de modal */
body.modal-open {
    overflow: hidden;
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
}

@media (min-width: 576px) {
    .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
    }
}
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Prevenir problemas con múltiples modales
    $('.modal').on('show.bs.modal', function () {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    // Resetear formularios cuando se cierra el modal
    $('.modal').on('hidden.bs.modal', function () {
        $(this).find('form')[0]?.reset();
    });

    // Prevenir scroll del body cuando el modal está abierto
    $('.modal').on('shown.bs.modal', function () {
        $('body').addClass('modal-open');
    });

    $('.modal').on('hidden.bs.modal', function () {
        if (!$('.modal.show').length) {
            $('body').removeClass('modal-open');
        }
    });
});
</script>
@stop
