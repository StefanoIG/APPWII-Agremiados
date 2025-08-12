@extends('adminlte::page')

@section('title', 'Competencias y Cursos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Competencias y Cursos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Competencias y Cursos</li>
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

    <div class="row mb-3">
        <div class="col-12">
            @can('admin')
                <a href="{{ route('competitions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Nueva Competencia
                </a>
            @endcan
            @hasrole('secretaria')
                <a href="{{ route('competitions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Crear Nueva Competencia
                </a>
            @endhasrole
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                @if(auth()->user()->hasAnyRole(['admin', 'secretaria']))
                    Todas las Competencias
                @else
                    Competencias Disponibles
                @endif
            </h3>
        </div>
        <div class="card-body">
            @if($competitions->count() > 0)
                <div class="row">
                    @foreach($competitions as $competition)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 {{ $competition->status === 'open' ? 'border-success' : '' }}">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">{{ $competition->name }}</h5>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge badge-{{ $competition->type === 'course' ? 'info' : 'primary' }}">
                                            {{ $competition->type === 'course' ? 'Curso' : 'Competencia' }}
                                        </span>
                                        <span class="badge badge-{{ 
                                            $competition->status === 'open' ? 'success' : 
                                            ($competition->status === 'draft' ? 'warning' :
                                            ($competition->status === 'rejected' ? 'danger' :
                                            ($competition->status === 'closed' ? 'secondary' : 
                                            ($competition->status === 'in_progress' ? 'info' : 'secondary')))) 
                                        }}">
                                            @if($competition->status === 'draft')
                                                Borrador
                                            @elseif($competition->status === 'open')
                                                Abierta
                                            @elseif($competition->status === 'rejected')
                                                Rechazada
                                            @elseif($competition->status === 'closed')
                                                Cerrada
                                            @elseif($competition->status === 'in_progress')
                                                En Progreso
                                            @else
                                                {{ ucfirst($competition->status) }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        <small class="text-muted">{{ Str::limit($competition->description, 100) }}</small>
                                    </p>
                                    <p class="mb-1">
                                        <strong>Disciplina:</strong> {{ $competition->disciplina->name ?? 'N/A' }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Categoría:</strong> {{ $competition->categoria->name ?? 'N/A' }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Miembros por equipo:</strong> {{ $competition->members_per_team }}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Equipos inscritos:</strong> {{ $competition->teams->count() }}
                                        @if($competition->max_teams)
                                            / {{ $competition->max_teams }}
                                        @endif
                                    </p>
                                    <p class="mb-2">
                                        <strong>Fecha de inicio:</strong> {{ $competition->start_date->format('d/m/Y') }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Límite de inscripción:</strong> 
                                        <span class="{{ $competition->registration_deadline < now() ? 'text-danger' : 'text-success' }}">
                                            {{ $competition->registration_deadline->format('d/m/Y') }}
                                        </span>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('competitions.show', $competition) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Ver Detalles
                                    </a>
                                    
                                    @if($competition->status === 'draft' && auth()->user()->hasRole('admin'))
                                        <div class="btn-group mt-2" role="group">
                                            <form action="{{ route('competitions.approve', $competition) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        onclick="return confirm('¿Aprobar esta competencia? Se enviarán notificaciones a todos los usuarios.')">
                                                    <i class="fas fa-check"></i> Aprobar
                                                </button>
                                            </form>
                                            <form action="{{ route('competitions.reject', $competition) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm ml-1" 
                                                        onclick="return confirm('¿Rechazar esta competencia?')">
                                                    <i class="fas fa-times"></i> Rechazar
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                    
                                    @if(auth()->user()->hasAnyRole(['admin', 'secretaria']) && $competition->status !== 'rejected')
                                        <a href="{{ route('competitions.edit', $competition) }}" class="btn btn-outline-warning btn-sm mt-2">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $competitions->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay competencias disponibles en este momento.</p>
                    @can('admin')
                        <a href="{{ route('competitions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear Primera Competencia
                        </a>
                    @endcan
                    @hasrole('secretaria')
                        <a href="{{ route('competitions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear Primera Competencia
                        </a>
                    @endhasrole
                </div>
            @endif
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@stop
