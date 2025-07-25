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
                        <hr>
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
                        </div>
                        <div class="col-md-6">
                            <h5>Fechas Importantes</h5>
                            <p><strong>Fecha de inicio:</strong> {{ $competition->start_date->format('d/m/Y') }}</p>
                            <p><strong>Fecha de fin:</strong> {{ $competition->end_date->format('d/m/Y') }}</p>
                            <p><strong>Límite de inscripción:</strong> 
                                <span class="{{ $competition->registration_deadline < now() ? 'text-danger' : 'text-success' }}">
                                    {{ $competition->registration_deadline->format('d/m/Y') }}
                                </span>
                            </p>
                            @if($competition->creator)
                                <p><strong>Creado por:</strong> {{ $competition->creator->name }}</p>
                            @endif
                            <p><strong>Creado el:</strong> {{ $competition->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @can('admin')
                        <a href="{{ route('competitions.edit', $competition) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Competencia
                        </a>
                    @endcan
                    @hasrole('secretaria')
                        <a href="{{ route('competitions.edit', $competition) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar Competencia
                        </a>
                    @endhasrole
                    <a href="{{ route('competitions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Competencias
                    </a>
                </div>
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
                        @if($userTeam)
                            <div class="alert alert-info">
                                <i class="fas fa-check"></i> Ya estás participando en el equipo: 
                                <strong>{{ $userTeam->name }}</strong>
                            </div>
                            <a href="#" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-eye"></i> Ver Mi Equipo
                            </a>
                        @elseif(!auth()->user()->canParticipateInCompetitions())
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
            <div class="card">
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
                    
                    @if($competition->teams->count() > 0)
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $competition->max_teams ? ($competition->teams->count() / $competition->max_teams * 100) : 100 }}%">
                            </div>
                        </div>
                    @endif
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
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $team->name }}</h5>
                                            <p class="card-text">
                                                <strong>Capitán:</strong> {{ $team->captain->name }}<br>
                                                <strong>Miembros:</strong> {{ $team->current_members }} / {{ $competition->max_members }}
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
@if($competition->isOpenForRegistration() && !$userTeam)
<div class="modal fade" id="createTeamModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Nuevo Equipo</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="team_name">Nombre del Equipo *</label>
                        <input type="text" class="form-control" id="team_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="team_description">Descripción (opcional)</label>
                        <textarea class="form-control" id="team_description" name="description" rows="3"></textarea>
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

<!-- Modal para Buscar Equipo -->
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
                <p>Aquí podrás buscar equipos disponibles y solicitar unirte.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-construction"></i> Esta funcionalidad estará disponible próximamente.
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@stop
