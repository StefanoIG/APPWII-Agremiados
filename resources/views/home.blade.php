@extends('adminlte::page')

@section('title', 'Dashboard - Agremiados')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Dashboard</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    {{-- Mensajes de estado --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
            {{ session('status') }}
        </div>
    @endif

    {{-- Tarjeta de bienvenida --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-home"></i>
                        Bienvenido, {{ Auth::user()->name }}!
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>¡Bienvenido al Sistema de Agremiados!</h5>
                            <p class="mb-0">
                                Has iniciado sesión exitosamente. Tu cuenta está activa y puedes acceder a todas las funcionalidades del sistema.
                            </p>
                            <hr>
                            <p class="text-muted">
                                <strong>Email:</strong> {{ Auth::user()->email }}<br>
                                <strong>Roles:</strong> 
                                @foreach(Auth::user()->roles as $role)
                                    <span class="badge badge-primary">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-user-check fa-5x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones rápidas según el rol --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt"></i>
                        Acciones Rápidas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Acciones para Admin --}}
                        @role('admin')
                            <div class="col-lg-3 col-md-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3><i class="fas fa-users-cog"></i></h3>
                                        <p>Panel de Admin</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <a href="{{ route('admin.dashboard') }}" class="small-box-footer">
                                        Ir al Panel <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3><i class="fas fa-users"></i></h3>
                                        <p>Gestionar Usuarios</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-edit"></i>
                                    </div>
                                    <a href="{{ route('users.index') }}" class="small-box-footer">
                                        Ver Usuarios <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endrole

                        {{-- Acciones para Secretaria --}}
                        @role('secretaria')
                            <div class="col-lg-3 col-md-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3><i class="fas fa-user-clock"></i></h3>
                                        <p>Usuarios Pendientes</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <a href="{{ route('secretaria.usuarios-pendientes') }}" class="small-box-footer">
                                        Revisar <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3><i class="fas fa-clipboard-check"></i></h3>
                                        <p>Panel Secretaria</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-clipboard"></i>
                                    </div>
                                    <a href="{{ route('secretaria.dashboard') }}" class="small-box-footer">
                                        Ir al Panel <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endrole

                        {{-- Acciones para Usuario común --}}
                        @role('user')
                            <div class="col-lg-3 col-md-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><i class="fas fa-user"></i></h3>
                                        <p>Mi Perfil</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        Ver Perfil <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endrole

                        {{-- Acción común para todos --}}
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3><i class="fas fa-sign-out-alt"></i></h3>
                                    <p>Cerrar Sesión</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <a href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                                   class="small-box-footer">
                                    Salir <i class="fas fa-arrow-circle-right"></i>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Información del sistema --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Información del Sistema
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <span class="description-percentage text-success">
                                    <i class="fas fa-caret-up"></i> 100%
                                </span>
                                <h5 class="description-header">Sistema</h5>
                                <span class="description-text">OPERATIVO</span>
                            </div>
                        </div>
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <span class="description-percentage text-primary">
                                    <i class="fas fa-shield-alt"></i>
                                </span>
                                <h5 class="description-header">Seguridad</h5>
                                <span class="description-text">ACTIVA</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="description-block">
                                <span class="description-percentage text-warning">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <h5 class="description-header">Última Conexión</h5>
                                <span class="description-text">{{ now()->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bell"></i>
                        Notificaciones
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> ¡Bienvenido!</h5>
                        Tu cuenta está activa y lista para usar.
                    </div>
                    
                    @role('secretaria')
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-info"></i> Recordatorio</h5>
                            Recuerda revisar los usuarios pendientes de aprobación.
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .description-block {
            text-align: center;
        }
        
        .small-box:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            margin-bottom: 1rem;
        }
        
        .badge {
            font-size: 0.75em;
        }
    </style>
@stop

@section('js')
    <script>
        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        console.log('Dashboard cargado correctamente');
    </script>
@stop
