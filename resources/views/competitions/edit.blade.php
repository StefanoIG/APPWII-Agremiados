@extends('adminlte::page')

@section('title', 'Editar Competencia')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Editar Competencia: {{ $competition->name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('competitions.index') }}">Competencias</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('competitions.show', $competition) }}">{{ $competition->name }}</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-edit"></i> Editar Información de la Competencia</h3>
                </div>
                <form action="{{ route('competitions.update', $competition) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Nombre de la Competencia *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $competition->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $competition->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="disciplina_id">Disciplina *</label>
                                    <select class="form-control @error('disciplina_id') is-invalid @enderror" 
                                            id="disciplina_id" name="disciplina_id" required>
                                        <option value="">Seleccionar disciplina</option>
                                        @foreach($disciplinas as $disciplina)
                                            <option value="{{ $disciplina->id }}" 
                                                {{ old('disciplina_id', $competition->disciplina_id) == $disciplina->id ? 'selected' : '' }}>
                                                {{ $disciplina->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('disciplina_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria_id">Categoría *</label>
                                    <select class="form-control @error('categoria_id') is-invalid @enderror" 
                                            id="categoria_id" name="categoria_id" required>
                                        <option value="">Seleccionar categoría</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" 
                                                {{ old('categoria_id', $competition->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="members_per_team">Miembros por Equipo *</label>
                                    <input type="number" class="form-control @error('members_per_team') is-invalid @enderror" 
                                           id="members_per_team" name="members_per_team" 
                                           value="{{ old('members_per_team', $competition->members_per_team) }}" 
                                           min="1" required>
                                    @error('members_per_team')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="max_members">Máximo de Miembros *</label>
                                    <input type="number" class="form-control @error('max_members') is-invalid @enderror" 
                                           id="max_members" name="max_members" 
                                           value="{{ old('max_members', $competition->max_members) }}" 
                                           min="1" required>
                                    @error('max_members')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="min_members">Mínimo de Miembros *</label>
                                    <input type="number" class="form-control @error('min_members') is-invalid @enderror" 
                                           id="min_members" name="min_members" 
                                           value="{{ old('min_members', $competition->min_members) }}" 
                                           min="1" required>
                                    @error('min_members')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="max_teams">Número Máximo de Equipos (opcional)</label>
                            <input type="number" class="form-control @error('max_teams') is-invalid @enderror" 
                                   id="max_teams" name="max_teams" 
                                   value="{{ old('max_teams', $competition->max_teams) }}" min="1">
                            <small class="form-text text-muted">Dejar vacío para sin límite</small>
                            @error('max_teams')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Fecha de Inicio *</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" 
                                           value="{{ old('start_date', $competition->start_date->format('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">Fecha de Fin *</label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" 
                                           value="{{ old('end_date', $competition->end_date->format('Y-m-d')) }}" required>
                                    @error('end_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="registration_deadline">Límite de Inscripción *</label>
                                    <input type="date" class="form-control @error('registration_deadline') is-invalid @enderror" 
                                           id="registration_deadline" name="registration_deadline" 
                                           value="{{ old('registration_deadline', $competition->registration_deadline->format('Y-m-d')) }}" required>
                                    @error('registration_deadline')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status">Estado de la Competencia</label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status">
                                <option value="upcoming" {{ old('status', $competition->status) == 'upcoming' ? 'selected' : '' }}>
                                    Próxima
                                </option>
                                <option value="registration_open" {{ old('status', $competition->status) == 'registration_open' ? 'selected' : '' }}>
                                    Inscripciones Abiertas
                                </option>
                                <option value="registration_closed" {{ old('status', $competition->status) == 'registration_closed' ? 'selected' : '' }}>
                                    Inscripciones Cerradas
                                </option>
                                <option value="in_progress" {{ old('status', $competition->status) == 'in_progress' ? 'selected' : '' }}>
                                    En Progreso
                                </option>
                                <option value="completed" {{ old('status', $competition->status) == 'completed' ? 'selected' : '' }}>
                                    Completada
                                </option>
                                <option value="cancelled" {{ old('status', $competition->status) == 'cancelled' ? 'selected' : '' }}>
                                    Cancelada
                                </option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Competencia
                        </button>
                        <a href="{{ route('competitions.show', $competition) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Auto-ajustar fechas relacionadas
    $('#start_date').change(function() {
        let startDate = $(this).val();
        if (startDate) {
            $('#end_date').attr('min', startDate);
            $('#registration_deadline').attr('max', startDate);
        }
    });
    
    $('#registration_deadline').change(function() {
        let regDate = $(this).val();
        if (regDate) {
            $('#start_date').attr('min', regDate);
        }
    });
    
    // Validar que min_members <= members_per_team <= max_members
    $('#min_members, #members_per_team, #max_members').change(function() {
        let minMembers = parseInt($('#min_members').val()) || 0;
        let membersPerTeam = parseInt($('#members_per_team').val()) || 0;
        let maxMembers = parseInt($('#max_members').val()) || 0;
        
        if (minMembers > membersPerTeam) {
            $('#members_per_team').val(minMembers);
        }
        
        if (membersPerTeam > maxMembers) {
            $('#max_members').val(membersPerTeam);
        }
    });
});
</script>
@stop
