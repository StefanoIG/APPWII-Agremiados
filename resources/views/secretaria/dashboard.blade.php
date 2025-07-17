@extends('adminlte::page')

@section('title', 'Panel Secretaria')

@section('content_header')
    <h1>Panel de Secretaria</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingUsersCount }}</h3>
                    <p>Usuarios Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <a href="{{ route('secretaria.usuarios-pendientes') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('secretaria.usuarios-pendientes') }}" class="btn btn-warning btn-lg btn-block">
                                <i class="fas fa-user-clock"></i> Revisar Usuarios Pendientes
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('users.index') }}" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-users"></i> Gestionar Usuarios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($pendingUsersCount > 0)
        <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Atención!</h5>
            Tienes {{ $pendingUsersCount }} usuario(s) pendiente(s) de revisión.
        </div>
    @endif
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Dashboard de Secretaria cargado'); </script>
@stop