@extends('adminlte::page')

@section('title', 'Mis Invitaciones')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Mis Invitaciones de Equipos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('competitions.index') }}">Competencias</a></li>
                    <li class="breadcrumb-item active">Invitaciones</li>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-envelope"></i> Invitaciones Recibidas</h3>
        </div>
        <div class="card-body">
            @if($invitations->count() > 0)
                @foreach($invitations as $invitation)
                    <div class="card mb-3 border-left-{{ $invitation->status === 'pending' ? 'warning' : ($invitation->status === 'accepted' ? 'success' : 'danger') }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title">
                                        Invitación para unirse a "{{ $invitation->team->name }}"
                                        <span class="badge badge-{{ 
                                            $invitation->status === 'pending' ? 'warning' : 
                                            ($invitation->status === 'accepted' ? 'success' : 'danger') 
                                        }}">
                                            {{ ucfirst($invitation->status) }}
                                        </span>
                                    </h5>
                                    <p class="card-text">
                                        <strong>Competencia:</strong> {{ $invitation->team->competition->name }}<br>
                                        <strong>Invitado por:</strong> {{ $invitation->inviter->name }}<br>
                                        <strong>Fecha de invitación:</strong> {{ $invitation->created_at->format('d/m/Y H:i') }}
                                        @if($invitation->expires_at)
                                            <br><strong>Expira:</strong> {{ $invitation->expires_at->format('d/m/Y H:i') }}
                                            @if($invitation->status === 'pending' && $invitation->isExpired())
                                                <span class="text-danger">(Expirada)</span>
                                            @endif
                                        @endif
                                    </p>
                                    @if($invitation->message)
                                        <div class="alert alert-light">
                                            <strong>Mensaje:</strong> {{ $invitation->message }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 text-right">
                                    @if($invitation->status === 'pending' && !$invitation->isExpired())
                                        <form action="{{ route('invitations.accept', $invitation) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success mb-2" onclick="return confirm('¿Aceptar esta invitación?')">
                                                <i class="fas fa-check"></i> Aceptar
                                            </button>
                                        </form>
                                        <br>
                                        <form action="{{ route('invitations.reject', $invitation) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Rechazar esta invitación?')">
                                                <i class="fas fa-times"></i> Rechazar
                                            </button>
                                        </form>
                                    @elseif($invitation->status === 'pending' && $invitation->isExpired())
                                        <span class="text-muted">Invitación expirada</span>
                                    @else
                                        <span class="text-muted">
                                            {{ $invitation->status === 'accepted' ? 'Aceptada' : 'Rechazada' }}
                                            @if($invitation->responded_at)
                                                <br><small>{{ $invitation->responded_at->format('d/m/Y H:i') }}</small>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-center">
                    {{ $invitations->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No tienes invitaciones de equipos.</p>
                    <a href="{{ route('competitions.index') }}" class="btn btn-primary">
                        <i class="fas fa-trophy"></i> Ver Competencias Disponibles
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@stop
