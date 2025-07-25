@extends('adminlte::page')

@section('title', 'Mis Equipos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Mis Equipos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('competitions.index') }}">Competencias</a></li>
                    <li class="breadcrumb-item active">Mis Equipos</li>
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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> Equipos en los que Participas</h3>
        </div>
        <div class="card-body">
            @if($teams->count() > 0)
                <div class="row">
                    @foreach($teams as $team)
                        @php
                            $userMember = $team->members->where('user_id', auth()->id())->first();
                            $isCaptain = $userMember && $userMember->is_captain;
                        @endphp
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 {{ $isCaptain ? 'border-warning' : 'border-primary' }}">
                                <div class="card-header {{ $isCaptain ? 'bg-warning' : 'bg-primary' }}">
                                    <h5 class="card-title mb-0 text-white">
                                        {{ $team->name }}
                                        @if($isCaptain)
                                            <span class="badge badge-light ml-2">
                                                <i class="fas fa-crown"></i> Capitán
                                            </span>
                                        @endif
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-muted">{{ $team->competition->name }}</h6>
                                    <p class="card-text">
                                        <strong>Disciplina:</strong> {{ $team->competition->disciplina->name ?? 'N/A' }}<br>
                                        <strong>Categoría:</strong> {{ $team->competition->categoria->name ?? 'N/A' }}
                                    </p>
                                    
                                    <hr>
                                    
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <h6 class="text-primary">{{ $team->current_members }}</h6>
                                            <small class="text-muted">Miembros</small>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="text-success">{{ $team->competition->max_members }}</h6>
                                            <small class="text-muted">Máximo</small>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="text-info">{{ $team->competition->members_per_team }}</h6>
                                            <small class="text-muted">Por Juego</small>
                                        </div>
                                    </div>

                                    <!-- Progress bar -->
                                    <div class="progress mt-2 mb-3">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ ($team->current_members / $team->competition->max_members) * 100 }}%">
                                        </div>
                                    </div>

                                    <p class="mb-2">
                                        <strong>Estado del Equipo:</strong>
                                        <span class="badge badge-{{ $team->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($team->status) }}
                                        </span>
                                    </p>

                                    <p class="mb-2">
                                        <strong>Estado de la Competencia:</strong>
                                        <span class="badge badge-{{ 
                                            $team->competition->status === 'open' ? 'success' : 
                                            ($team->competition->status === 'closed' ? 'warning' : 
                                            ($team->competition->status === 'in_progress' ? 'info' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($team->competition->status) }}
                                        </span>
                                    </p>

                                    <p class="mb-0">
                                        <small class="text-muted">
                                            <strong>Inicio:</strong> {{ $team->competition->start_date->format('d/m/Y') }}
                                        </small>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('competitions.show', $team->competition) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> Ver Competencia
                                        </a>
                                        @if($isCaptain)
                                            <button class="btn btn-outline-warning btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#manageTeamModal{{ $team->id }}">
                                                <i class="fas fa-cog"></i> Gestionar
                                            </button>
                                        @endif
                                    </div>

                                    @if($isCaptain && $team->competition->isOpenForRegistration())
                                        <button class="btn btn-outline-success btn-sm btn-block mt-2" 
                                                data-toggle="modal" 
                                                data-target="#invitePlayerModal{{ $team->id }}">
                                            <i class="fas fa-user-plus"></i> Invitar Jugador
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($isCaptain)
                            <!-- Modal para Gestionar Equipo -->
                            <div class="modal fade" id="manageTeamModal{{ $team->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-cog"></i> Gestionar Equipo: {{ $team->name }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Miembros del Equipo</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Estado</th>
                                                            <th>Fecha de Unión</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($team->members as $member)
                                                            <tr>
                                                                <td>
                                                                    {{ $member->user->name }}
                                                                    @if($member->is_captain)
                                                                        <i class="fas fa-crown text-warning ml-1"></i>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <span class="badge badge-{{ $member->status === 'active' ? 'success' : 'secondary' }}">
                                                                        {{ ucfirst($member->status) }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $member->joined_at->format('d/m/Y') }}</td>
                                                                <td>
                                                                    @if(!$member->is_captain)
                                                                        <button class="btn btn-danger btn-xs">
                                                                            <i class="fas fa-times"></i> Expulsar
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal para Invitar Jugador -->
                            <div class="modal fade" id="invitePlayerModal{{ $team->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-user-plus"></i> Invitar Jugador
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="#" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="search_user">Buscar Usuario</label>
                                                    <input type="text" class="form-control" id="search_user" 
                                                           placeholder="Nombre, cédula, teléfono o email...">
                                                    <small class="form-text text-muted">
                                                        Puedes buscar por nombre, número de cédula, teléfono o email
                                                    </small>
                                                </div>
                                                <div id="search_results" class="mt-3">
                                                    <!-- Resultados de búsqueda aparecerán aquí -->
                                                </div>
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle"></i> 
                                                    Esta funcionalidad estará disponible próximamente.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No tienes equipos aún</h4>
                    <p class="text-muted">Únete a una competencia para formar parte de un equipo.</p>
                    <a href="{{ route('competitions.index') }}" class="btn btn-primary">
                        <i class="fas fa-trophy"></i> Ver Competencias Disponibles
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .border-warning {
        border-width: 2px !important;
    }
    .border-primary {
        border-width: 2px !important;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@stop
