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
                        <div class="champions-bracket">
                            <!-- Lado Izquierdo -->
                            <div class="bracket-side left-bracket">
                                @for($round = 1; $round <= $maxRounds; $round++)
                                    <div class="bracket-column">
                                        <div class="round-header">
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
                                        <div class="round-matches">
                                            @if(isset($leftBrackets[$round]))
                                                @foreach($leftBrackets[$round] as $match)
                                                    <div class="match-container">
                                                        <div class="match {{ $match->status }}">
                                                            <div class="team {{ $match->winner_id == $match->team1_id ? 'winner' : '' }}">
                                                                @if($match->team1)
                                                                    <div class="team-content">
                                                                        @if($match->team1->logo)
                                                                            <img src="{{ asset($match->team1->logo) }}" alt="{{ $match->team1->name }}" class="team-logo">
                                                                        @endif
                                                                        <span class="team-name">{{ $match->team1->name }}</span>
                                                                    </div>
                                                                    @if($match->team1_score !== null)
                                                                        <span class="score">{{ $match->team1_score }}</span>
                                                                    @endif
                                                                @else
                                                                    <div class="team-content bye">
                                                                        <span class="team-name">BYE</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="team {{ $match->winner_id == $match->team2_id ? 'winner' : '' }}">
                                                                @if($match->team2)
                                                                    <div class="team-content">
                                                                        @if($match->team2->logo)
                                                                            <img src="{{ asset($match->team2->logo) }}" alt="{{ $match->team2->name }}" class="team-logo">
                                                                        @endif
                                                                        <span class="team-name">{{ $match->team2->name }}</span>
                                                                    </div>
                                                                    @if($match->team2_score !== null)
                                                                        <span class="score">{{ $match->team2_score }}</span>
                                                                    @endif
                                                                @else
                                                                    <div class="team-content bye">
                                                                        <span class="team-name">BYE</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if($round < $maxRounds)
                                                            <div class="connector-line"></div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <!-- Final Central -->
                            <div class="final-section">
                                <div class="final-trophy">
                                    <i class="fas fa-trophy"></i>
                                    <h3>FINAL</h3>
                                </div>
                                @if($finalMatch)
                                    <div class="final-match {{ $finalMatch->status }}">
                                        <div class="team {{ $finalMatch->winner_id == $finalMatch->team1_id ? 'winner' : '' }}">
                                            @if($finalMatch->team1)
                                                <div class="team-content">
                                                    @if($finalMatch->team1->logo)
                                                        <img src="{{ asset($finalMatch->team1->logo) }}" alt="{{ $finalMatch->team1->name }}" class="team-logo">
                                                    @endif
                                                    <span class="team-name">{{ $finalMatch->team1->name }}</span>
                                                </div>
                                                @if($finalMatch->team1_score !== null)
                                                    <span class="score">{{ $finalMatch->team1_score }}</span>
                                                @endif
                                            @else
                                                <div class="team-content pending">
                                                    <span class="team-name">Ganador Izq.</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="team {{ $finalMatch->winner_id == $finalMatch->team2_id ? 'winner' : '' }}">
                                            @if($finalMatch->team2)
                                                <div class="team-content">
                                                    @if($finalMatch->team2->logo)
                                                        <img src="{{ asset($finalMatch->team2->logo) }}" alt="{{ $finalMatch->team2->name }}" class="team-logo">
                                                    @endif
                                                    <span class="team-name">{{ $finalMatch->team2->name }}</span>
                                                </div>
                                                @if($finalMatch->team2_score !== null)
                                                    <span class="score">{{ $finalMatch->team2_score }}</span>
                                                @endif
                                            @else
                                                <div class="team-content pending">
                                                    <span class="team-name">Ganador Der.</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="final-match pending">
                                        <div class="team">
                                            <div class="team-content pending">
                                                <span class="team-name">Ganador Izquierdo</span>
                                            </div>
                                        </div>
                                        <div class="team">
                                            <div class="team-content pending">
                                                <span class="team-name">Ganador Derecho</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Lado Derecho -->
                            <div class="bracket-side right-bracket">
                                @for($round = 1; $round <= $maxRounds; $round++)
                                    <div class="bracket-column">
                                        <div class="round-header">
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
                                        <div class="round-matches">
                                            @if(isset($rightBrackets[$round]))
                                                @foreach($rightBrackets[$round] as $match)
                                                    <div class="match-container">
                                                        @if($round < $maxRounds)
                                                            <div class="connector-line"></div>
                                                        @endif
                                                        <div class="match {{ $match->status }}">
                                                            <div class="team {{ $match->winner_id == $match->team1_id ? 'winner' : '' }}">
                                                                @if($match->team1)
                                                                    <div class="team-content">
                                                                        @if($match->team1->logo)
                                                                            <img src="{{ asset($match->team1->logo) }}" alt="{{ $match->team1->name }}" class="team-logo">
                                                                        @endif
                                                                        <span class="team-name">{{ $match->team1->name }}</span>
                                                                    </div>
                                                                    @if($match->team1_score !== null)
                                                                        <span class="score">{{ $match->team1_score }}</span>
                                                                    @endif
                                                                @else
                                                                    <div class="team-content bye">
                                                                        <span class="team-name">BYE</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="team {{ $match->winner_id == $match->team2_id ? 'winner' : '' }}">
                                                                @if($match->team2)
                                                                    <div class="team-content">
                                                                        @if($match->team2->logo)
                                                                            <img src="{{ asset($match->team2->logo) }}" alt="{{ $match->team2->name }}" class="team-logo">
                                                                        @endif
                                                                        <span class="team-name">{{ $match->team2->name }}</span>
                                                                    </div>
                                                                    @if($match->team2_score !== null)
                                                                        <span class="score">{{ $match->team2_score }}</span>
                                                                    @endif
                                                                @else
                                                                    <div class="team-content bye">
                                                                        <span class="team-name">BYE</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endfor
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
    .champions-bracket {
        display: flex;
        min-height: 100vh;
        background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        overflow-x: auto;
        padding: 20px;
        position: relative;
    }

    .bracket-side {
        display: flex;
        flex: 1;
        align-items: stretch;
    }

    .left-bracket {
        justify-content: flex-end;
        margin-right: 20px;
    }

    .right-bracket {
        justify-content: flex-start;
        margin-left: 20px;
        flex-direction: row-reverse;
    }

    .bracket-column {
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin: 0 15px;
        min-width: 200px;
    }

    .round-header {
        text-align: center;
        color: #ffd700;
        font-weight: bold;
        font-size: 1rem;
        margin-bottom: 20px;
        background: rgba(255, 215, 0, 0.1);
        padding: 8px 12px;
        border-radius: 20px;
        border: 2px solid #ffd700;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    .round-matches {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 30px;
    }

    .match-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .left-bracket .match-container {
        flex-direction: row;
    }

    .right-bracket .match-container {
        flex-direction: row-reverse;
    }

    .match {
        background: linear-gradient(145deg, #ffffff, #f0f0f0);
        border-radius: 12px;
        padding: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        border: 2px solid #ddd;
        min-width: 180px;
        transition: all 0.3s ease;
    }

    .match:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.4);
        border-color: #007bff;
    }

    .team {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 10px;
        margin: 3px 0;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #007bff;
        transition: all 0.3s ease;
    }

    .team.winner {
        background: linear-gradient(145deg, #d4edda, #c3e6cb);
        border-left-color: #28a745;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .team-content {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
    }

    .team-logo {
        width: 24px;
        height: 24px;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #ddd;
    }

    .team-name {
        font-size: 0.85rem;
        font-weight: 500;
        color: #333;
    }

    .score {
        font-weight: bold;
        font-size: 0.9rem;
        color: #007bff;
        background: #e3f2fd;
        padding: 2px 6px;
        border-radius: 4px;
        min-width: 20px;
        text-align: center;
    }

    .connector-line {
        width: 30px;
        height: 2px;
        background: #ffd700;
        position: relative;
    }

    .left-bracket .connector-line {
        margin-left: 10px;
    }

    .right-bracket .connector-line {
        margin-right: 10px;
    }

    .connector-line::before,
    .connector-line::after {
        content: '';
        position: absolute;
        width: 2px;
        height: 40px;
        background: #ffd700;
        right: 0;
    }

    .connector-line::before {
        top: -40px;
    }

    .connector-line::after {
        bottom: -40px;
    }

    .final-section {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-width: 280px;
        max-width: 280px;
        padding: 20px;
        background: linear-gradient(145deg, rgba(255,215,0,0.2), rgba(255,215,0,0.1));
        border-radius: 20px;
        border: 3px solid #ffd700;
        backdrop-filter: blur(10px);
        position: relative;
    }

    .final-trophy {
        text-align: center;
        margin-bottom: 30px;
    }

    .final-trophy i {
        font-size: 4rem;
        color: #ffd700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        margin-bottom: 15px;
        animation: pulse 2s ease-in-out infinite alternate;
    }

    .final-trophy h3 {
        color: #ffd700;
        font-size: 1.8rem;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        margin: 0;
        letter-spacing: 2px;
    }

    .final-match {
        background: linear-gradient(145deg, #ffd700, #ffed4e);
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        border: 3px solid #ffb300;
        color: #333;
        width: 100%;
    }

    .final-match .team {
        margin: 8px 0;
        padding: 12px;
        border-left-width: 6px;
        font-size: 1.1rem;
    }

    .final-match .team-name {
        font-size: 1rem;
        font-weight: 600;
    }

    .final-match .score {
        font-size: 1.2rem;
        background: rgba(0,123,255,0.2);
        color: #0056b3;
    }

    @keyframes pulse {
        from { transform: scale(1); }
        to { transform: scale(1.1); }
    }

    .team-content.bye,
    .team-content.pending {
        opacity: 0.6;
        font-style: italic;
        color: #6c757d;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .bracket-column {
            min-width: 160px;
            margin: 0 10px;
        }
        
        .match {
            min-width: 150px;
            padding: 10px;
        }
        
        .final-section {
            min-width: 240px;
            max-width: 240px;
        }
    }

    @media (max-width: 768px) {
        .champions-bracket {
            flex-direction: column;
            padding: 10px;
        }
        
        .bracket-side {
            margin: 10px 0;
        }
        
        .final-section {
            min-width: auto;
            max-width: none;
        }
    }
</style>
@stop
