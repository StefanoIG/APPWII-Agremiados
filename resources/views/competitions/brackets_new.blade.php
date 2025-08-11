@extends('adminlte::page')

@section('title', 'Brackets - ' . $competition->name)

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Brackets de Torneo</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('competitions.index') }}">Competencias</a></li>
                <li class="breadcrumb-item"><a href="{{ route('competitions.show', $competition) }}">{{ $competition->name }}</a></li>
                <li class="breadcrumb-item active">Brackets</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-trophy mr-2"></i>
                        Brackets de {{ $competition->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('competitions.show', $competition) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($leftBrackets->count() > 0 || $rightBrackets->count() > 0)
                        <div class="tournament-container">
                            <!-- Lado Izquierdo -->
                            <div class="tournament-side left-side">
                                <h4 class="side-title">Lado Izquierdo</h4>
                                <div class="bracket-rounds">
                                    @for($round = 1; $round <= $maxRounds; $round++)
                                        <div class="tournament-round">
                                            <div class="round-title">
                                                @if($round == 1)
                                                    Primera Ronda
                                                @elseif($round == 2)
                                                    Octavos
                                                @elseif($round == 3)
                                                    Cuartos
                                                @elseif($round == 4)
                                                    Semifinal
                                                @else
                                                    Ronda {{ $round }}
                                                @endif
                                            </div>
                                            <div class="matches">
                                                @if(isset($leftBrackets[$round]))
                                                    @foreach($leftBrackets[$round] as $match)
                                                        <div class="match {{ $match->status }}">
                                                            <div class="match-teams">
                                                                <div class="team {{ $match->winner_id == $match->team1_id ? 'winner' : '' }}">
                                                                    @if($match->team1)
                                                                        <div class="team-info">
                                                                            @if($match->team1->team->logo)
                                                                                <img src="{{ asset($match->team1->team->logo) }}" alt="{{ $match->team1->team->name }}" class="team-logo">
                                                                            @endif
                                                                            <span class="team-name">{{ $match->team1->team->name }}</span>
                                                                        </div>
                                                                        @if($match->team1_score !== null)
                                                                            <span class="score">{{ $match->team1_score }}</span>
                                                                        @endif
                                                                    @else
                                                                        <div class="team-info bye">
                                                                            <span class="team-name">BYE</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="vs">VS</div>
                                                                <div class="team {{ $match->winner_id == $match->team2_id ? 'winner' : '' }}">
                                                                    @if($match->team2)
                                                                        <div class="team-info">
                                                                            @if($match->team2->team->logo)
                                                                                <img src="{{ asset($match->team2->team->logo) }}" alt="{{ $match->team2->team->name }}" class="team-logo">
                                                                            @endif
                                                                            <span class="team-name">{{ $match->team2->team->name }}</span>
                                                                        </div>
                                                                        @if($match->team2_score !== null)
                                                                            <span class="score">{{ $match->team2_score }}</span>
                                                                        @endif
                                                                    @else
                                                                        <div class="team-info bye">
                                                                            <span class="team-name">BYE</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="match-status">
                                                                @if($match->status == 'completed')
                                                                    <span class="badge badge-success">Finalizado</span>
                                                                @elseif($match->status == 'in_progress')
                                                                    <span class="badge badge-warning">En Progreso</span>
                                                                @else
                                                                    <span class="badge badge-secondary">Pendiente</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Final -->
                            <div class="tournament-final">
                                <div class="final-trophy">
                                    <i class="fas fa-trophy"></i>
                                    <h3>FINAL</h3>
                                </div>
                                @if($finalMatch)
                                    <div class="match final-match {{ $finalMatch->status }}">
                                        <div class="match-teams">
                                            <div class="team {{ $finalMatch->winner_id == $finalMatch->team1_id ? 'winner' : '' }}">
                                                @if($finalMatch->team1)
                                                    <div class="team-info">
                                                        @if($finalMatch->team1->team->logo)
                                                            <img src="{{ asset($finalMatch->team1->team->logo) }}" alt="{{ $finalMatch->team1->team->name }}" class="team-logo">
                                                        @endif
                                                        <span class="team-name">{{ $finalMatch->team1->team->name }}</span>
                                                    </div>
                                                    @if($finalMatch->team1_score !== null)
                                                        <span class="score">{{ $finalMatch->team1_score }}</span>
                                                    @endif
                                                @else
                                                    <div class="team-info pending">
                                                        <span class="team-name">Ganador Izq.</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="vs final-vs">VS</div>
                                            <div class="team {{ $finalMatch->winner_id == $finalMatch->team2_id ? 'winner' : '' }}">
                                                @if($finalMatch->team2)
                                                    <div class="team-info">
                                                        @if($finalMatch->team2->team->logo)
                                                            <img src="{{ asset($finalMatch->team2->team->logo) }}" alt="{{ $finalMatch->team2->team->name }}" class="team-logo">
                                                        @endif
                                                        <span class="team-name">{{ $finalMatch->team2->team->name }}</span>
                                                    </div>
                                                    @if($finalMatch->team2_score !== null)
                                                        <span class="score">{{ $finalMatch->team2_score }}</span>
                                                    @endif
                                                @else
                                                    <div class="team-info pending">
                                                        <span class="team-name">Ganador Der.</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="match-status">
                                            @if($finalMatch->status == 'completed')
                                                <span class="badge badge-success">Finalizado</span>
                                            @elseif($finalMatch->status == 'in_progress')
                                                <span class="badge badge-warning">En Progreso</span>
                                            @else
                                                <span class="badge badge-secondary">Pendiente</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="match final-match pending">
                                        <div class="match-teams">
                                            <div class="team">
                                                <div class="team-info pending">
                                                    <span class="team-name">Ganador Izquierdo</span>
                                                </div>
                                            </div>
                                            <div class="vs final-vs">VS</div>
                                            <div class="team">
                                                <div class="team-info pending">
                                                    <span class="team-name">Ganador Derecho</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="match-status">
                                            <span class="badge badge-secondary">Pendiente</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Lado Derecho -->
                            <div class="tournament-side right-side">
                                <h4 class="side-title">Lado Derecho</h4>
                                <div class="bracket-rounds">
                                    @for($round = 1; $round <= $maxRounds; $round++)
                                        <div class="tournament-round">
                                            <div class="round-title">
                                                @if($round == 1)
                                                    Primera Ronda
                                                @elseif($round == 2)
                                                    Octavos
                                                @elseif($round == 3)
                                                    Cuartos
                                                @elseif($round == 4)
                                                    Semifinal
                                                @else
                                                    Ronda {{ $round }}
                                                @endif
                                            </div>
                                            <div class="matches">
                                                @if(isset($rightBrackets[$round]))
                                                    @foreach($rightBrackets[$round] as $match)
                                                        <div class="match {{ $match->status }}">
                                                            <div class="match-teams">
                                                                <div class="team {{ $match->winner_id == $match->team1_id ? 'winner' : '' }}">
                                                                    @if($match->team1)
                                                                        <div class="team-info">
                                                                            @if($match->team1->team->logo)
                                                                                <img src="{{ asset($match->team1->team->logo) }}" alt="{{ $match->team1->team->name }}" class="team-logo">
                                                                            @endif
                                                                            <span class="team-name">{{ $match->team1->team->name }}</span>
                                                                        </div>
                                                                        @if($match->team1_score !== null)
                                                                            <span class="score">{{ $match->team1_score }}</span>
                                                                        @endif
                                                                    @else
                                                                        <div class="team-info bye">
                                                                            <span class="team-name">BYE</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="vs">VS</div>
                                                                <div class="team {{ $match->winner_id == $match->team2_id ? 'winner' : '' }}">
                                                                    @if($match->team2)
                                                                        <div class="team-info">
                                                                            @if($match->team2->team->logo)
                                                                                <img src="{{ asset($match->team2->team->logo) }}" alt="{{ $match->team2->team->name }}" class="team-logo">
                                                                            @endif
                                                                            <span class="team-name">{{ $match->team2->team->name }}</span>
                                                                        </div>
                                                                        @if($match->team2_score !== null)
                                                                            <span class="score">{{ $match->team2_score }}</span>
                                                                        @endif
                                                                    @else
                                                                        <div class="team-info bye">
                                                                            <span class="team-name">BYE</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="match-status">
                                                                @if($match->status == 'completed')
                                                                    <span class="badge badge-success">Finalizado</span>
                                                                @elseif($match->status == 'in_progress')
                                                                    <span class="badge badge-warning">En Progreso</span>
                                                                @else
                                                                    <span class="badge badge-secondary">Pendiente</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center p-5">
                            <i class="fas fa-bracket-curly fa-4x text-muted mb-3"></i>
                            <h4>No hay brackets generados</h4>
                            <p class="text-muted">Genera los brackets desde la vista de la competencia.</p>
                            <a href="{{ route('competitions.show', $competition) }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Volver a la Competencia
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .tournament-container {
        display: flex;
        min-height: 80vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        overflow-x: auto;
        padding: 20px;
        position: relative;
    }

    .tournament-side {
        flex: 1;
        min-width: 400px;
        padding: 0 20px;
    }

    .tournament-final {
        flex: 0 0 300px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        margin: 0 20px;
        backdrop-filter: blur(10px);
    }

    .side-title {
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.5rem;
        font-weight: bold;
        color: #ffd700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .bracket-rounds {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .tournament-round {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .round-title {
        text-align: center;
        font-weight: bold;
        padding: 10px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        margin-bottom: 10px;
        font-size: 1.1rem;
    }

    .matches {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .match {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        color: #333;
    }

    .match:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }

    .match-teams {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .team {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
    }

    .team.winner {
        background: #d4edda;
        border-left-color: #28a745;
        font-weight: bold;
    }

    .team-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .team-logo {
        width: 30px;
        height: 30px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .team-name {
        font-weight: 500;
        color: #333;
    }

    .score {
        font-weight: bold;
        font-size: 1.1rem;
        color: #007bff;
        background: #e3f2fd;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .vs {
        text-align: center;
        font-weight: bold;
        color: #6c757d;
        padding: 5px;
        font-size: 0.9rem;
    }

    .match-status {
        margin-top: 10px;
        text-align: center;
    }

    .final-trophy {
        text-align: center;
        margin-bottom: 30px;
    }

    .final-trophy i {
        font-size: 3rem;
        color: #ffd700;
        margin-bottom: 10px;
    }

    .final-trophy h3 {
        color: #ffd700;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        margin: 0;
    }

    .final-match {
        width: 100%;
        background: linear-gradient(145deg, #ffd700, #ffed4e);
        border: 3px solid #ffb300;
        color: #333;
    }

    .final-vs {
        font-size: 1.2rem;
        color: #333;
        font-weight: bold;
    }

    .team-info.bye {
        opacity: 0.6;
        font-style: italic;
    }

    .team-info.pending {
        opacity: 0.7;
        font-style: italic;
        color: #6c757d;
    }

    .badge {
        font-size: 0.8rem;
        padding: 4px 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tournament-container {
            flex-direction: column;
            min-height: auto;
        }
        
        .tournament-side {
            min-width: auto;
            padding: 10px;
        }
        
        .tournament-final {
            flex: none;
            margin: 20px 0;
        }
        
        .side-title {
            font-size: 1.2rem;
        }
    }
</style>
@stop
