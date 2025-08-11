@extends('adminlte::page')

@section('title', 'Registrar Resultado - ' . $competition->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Registrar Resultado del Partido</h1>
        <a href="{{ route('competitions.brackets', $competition) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Brackets
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-trophy mr-2"></i>
                        {{ $competition->name }} - Round {{ $bracket->round }}
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Información del partido -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="match-info bg-light p-3 rounded">
                                <div class="row text-center">
                                    <div class="col-md-5">
                                        <h4 class="text-primary">
                                            <i class="fas fa-users mr-2"></i>
                                            {{ $bracket->team1->name ?? 'TBD' }}
                                        </h4>
                                        @if($bracket->team1)
                                            <small class="text-muted">
                                                Capitán: {{ $bracket->team1->members->where('is_captain', true)->first()->user->name ?? 'Sin capitán' }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <h3 class="text-center text-muted">VS</h3>
                                    </div>
                                    <div class="col-md-5">
                                        <h4 class="text-danger">
                                            <i class="fas fa-users mr-2"></i>
                                            {{ $bracket->team2->name ?? 'TBD' }}
                                        </h4>
                                        @if($bracket->team2)
                                            <small class="text-muted">
                                                Capitán: {{ $bracket->team2->members->where('is_captain', true)->first()->user->name ?? 'Sin capitán' }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($bracket->status === 'completed')
                        <!-- Mostrar resultado existente -->
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> Resultado ya registrado</h5>
                            <div class="row text-center mt-3">
                                <div class="col-md-5">
                                    <h2 class="text-primary">{{ $bracket->team1_score }}</h2>
                                </div>
                                <div class="col-md-2">
                                    <h3>-</h3>
                                </div>
                                <div class="col-md-5">
                                    <h2 class="text-danger">{{ $bracket->team2_score }}</h2>
                                </div>
                            </div>
                            @if($bracket->winner_id)
                                <p class="text-center mt-3">
                                    <strong>Ganador:</strong> 
                                    <span class="badge badge-success">
                                        {{ $bracket->winner_id == $bracket->team1_id ? $bracket->team1->name : $bracket->team2->name }}
                                    </span>
                                </p>
                            @endif
                            @if($bracket->notes)
                                <p><strong>Notas:</strong> {{ $bracket->notes }}</p>
                            @endif
                            @if($bracket->evidence_file)
                                <p>
                                    <a href="{{ route('competitions.match.evidence', [$competition, $bracket]) }}" 
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-download"></i> Descargar Evidencia
                                    </a>
                                </p>
                            @endif
                            <small class="text-muted">
                                Registrado por {{ $bracket->registeredBy->name ?? 'Sistema' }} 
                                el {{ $bracket->result_registered_at?->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    @else
                        <!-- Formulario para registrar resultado -->
                        <form action="{{ route('competitions.match.store', [$competition, $bracket]) }}" 
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="team1_score">
                                            Marcador - {{ $bracket->team1->name ?? 'Equipo 1' }}
                                        </label>
                                        <input type="number" 
                                               name="team1_score" 
                                               id="team1_score" 
                                               class="form-control form-control-lg text-center @error('team1_score') is-invalid @enderror" 
                                               value="{{ old('team1_score') }}" 
                                               min="0" 
                                               required>
                                        @error('team1_score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-2 text-center">
                                    <label>&nbsp;</label>
                                    <div class="pt-2">
                                        <h3 class="text-muted">-</h3>
                                    </div>
                                </div>
                                
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="team2_score">
                                            Marcador - {{ $bracket->team2->name ?? 'Equipo 2' }}
                                        </label>
                                        <input type="number" 
                                               name="team2_score" 
                                               id="team2_score" 
                                               class="form-control form-control-lg text-center @error('team2_score') is-invalid @enderror" 
                                               value="{{ old('team2_score') }}" 
                                               min="0" 
                                               required>
                                        @error('team2_score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="evidence">Evidencia del Resultado (Opcional)</label>
                                <input type="file" 
                                       name="evidence" 
                                       id="evidence" 
                                       class="form-control-file @error('evidence') is-invalid @enderror"
                                       accept=".jpg,.jpeg,.png,.pdf">
                                <small class="form-text text-muted">
                                    Sube una foto del marcador final o documento PDF. Máximo 2MB. 
                                    Formatos: JPG, PNG, PDF
                                </small>
                                @error('evidence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="notes">Notas del Partido (Opcional)</label>
                                <textarea name="notes" 
                                          id="notes" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          rows="3" 
                                          placeholder="Comentarios adicionales sobre el partido, incidencias, etc.">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Registrar Resultado
                                </button>
                                <a href="{{ route('competitions.brackets', $competition) }}" 
                                   class="btn btn-secondary btn-lg ml-2">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>
                        Información del Partido
                    </h3>
                </div>
                <div class="card-body">
                    <p><strong>Competencia:</strong> {{ $competition->name }}</p>
                    <p><strong>Categoría:</strong> {{ $competition->categoria->name }}</p>
                    <p><strong>Disciplina:</strong> {{ $competition->disciplina->name }}</p>
                    <p><strong>Ronda:</strong> {{ $bracket->round }}</p>
                    <p><strong>Posición:</strong> {{ $bracket->position }}</p>
                    @if($bracket->match_date)
                        <p><strong>Fecha programada:</strong> {{ $bracket->match_date->format('d/m/Y H:i') }}</p>
                    @endif
                    <p><strong>Estado:</strong> 
                        <span class="badge badge-{{ $bracket->status === 'completed' ? 'success' : 'warning' }}">
                            {{ ucfirst($bracket->status) }}
                        </span>
                    </p>
                </div>
            </div>

            @if($bracket->status !== 'completed')
                <div class="card mt-3">
                    <div class="card-header bg-warning">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Importante
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>No puede haber empates en los resultados</li>
                            <li>Verifica bien los marcadores antes de enviar</li>
                            <li>La evidencia es opcional pero recomendada</li>
                            <li>Una vez registrado, el ganador avanzará automáticamente</li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <style>
        .match-info {
            border: 2px solid #dee2e6;
        }
        .form-control-lg {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
        }
    </style>
@stop

@section('js')
    <script>
        // Validación en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const team1Score = document.getElementById('team1_score');
            const team2Score = document.getElementById('team2_score');
            
            function validateScores() {
                if (team1Score.value && team2Score.value) {
                    if (parseInt(team1Score.value) === parseInt(team2Score.value)) {
                        team1Score.classList.add('is-invalid');
                        team2Score.classList.add('is-invalid');
                    } else {
                        team1Score.classList.remove('is-invalid');
                        team2Score.classList.remove('is-invalid');
                    }
                }
            }
            
            team1Score.addEventListener('input', validateScores);
            team2Score.addEventListener('input', validateScores);
        });

        // Validación de archivo
        document.getElementById('evidence').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // MB
                if (fileSize > 2) {
                    alert('El archivo es demasiado grande. Máximo 2MB permitido.');
                    this.value = '';
                }
            }
        });
    </script>
@stop
