@extends('adminlte::page')

@section('title', 'Dashboard - Agremiados')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>
                Dashboard 
                @role('admin')
                    <small class="text-muted">- Administrador</small>
                @endrole
                @role('secretaria')
                    <small class="text-muted">- Secretaría</small>
                @endrole
                @role('user')
                    <small class="text-muted">- Usuario</small>
                @endrole
            </h1>
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

    {{-- Alertas importantes para Admin/Secretaria --}}
    @hasanyrole('admin|secretaria')
        @if(isset($competitionsNeedingBrackets) && $competitionsNeedingBrackets > 0)
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-exclamation-triangle"></i> ¡Atención!</h5>
                Hay {{ $competitionsNeedingBrackets }} competencia(s) llena(s) que necesitan generar brackets.
                <a href="{{ route('competitions.index') }}" class="btn btn-sm btn-warning ml-2">
                    <i class="fas fa-eye"></i> Ver Competencias
                </a>
            </div>
        @endif

        @if(isset($nextCompetitionDays) && $nextCompetitionDays <= 7)
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-calendar"></i> Próxima Competencia</h5>
                La competencia "{{ $nextCompetitionName }}" comienza en {{ $nextCompetitionDays }} día(s).
            </div>
        @endif
    @endhasanyrole

    {{-- Métricas principales según el rol --}}
    <div class="row">
        @hasanyrole('admin|secretaria')
            {{-- Métricas para Admin/Secretaria --}}
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $competitionsNeedingBrackets ?? 0 }}</h3>
                        <p>Brackets Pendientes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <a href="{{ route('competitions.index') }}" class="small-box-footer">
                        Ver Competencias <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $nextCompetitionDays ?? 'N/A' }}</h3>
                        <p>Días Próxima Competencia</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <a href="{{ route('competitions.index') }}" class="small-box-footer">
                        Ver Calendario <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $competitionsNearFull ?? 0 }}</h3>
                        <p>Competencias Casi Llenas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('competitions.index') }}" class="small-box-footer">
                        Revisar <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $pendingUsers ?? 0 }}</h3>
                        <p>Usuarios Pendientes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    @role('secretaria')
                        <a href="{{ route('secretaria.usuarios-pendientes') }}" class="small-box-footer">
                            Revisar <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    @else
                        <a href="{{ route('users.index') }}" class="small-box-footer">
                            Revisar <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    @endrole
                </div>
            </div>
        @endhasanyrole

        @role('user')
            {{-- Métricas para Usuario --}}
            <div class="col-lg-6 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $userTeams ?? 0 }}</h3>
                        <p>Mis Equipos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('competitions.teams') }}" class="small-box-footer">
                        Ver Equipos <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-6 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $availableCompetitions ?? 0 }}</h3>
                        <p>Competencias Disponibles</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <a href="{{ route('competitions.index') }}" class="small-box-footer">
                        Explorar <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @endrole
    </div>

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
                                @role('admin')
                                    Como administrador, tienes acceso completo al sistema. Puedes gestionar usuarios, competencias, y todas las configuraciones.
                                @endrole
                                @role('secretaria')
                                    Como secretaria, puedes gestionar usuarios, aprobar registros, crear competencias y supervisar el sistema.
                                @endrole
                                @role('user')
                                    ¡Bienvenido! Puedes unirte a competencias, formar equipos y participar en las actividades del club.
                                @endrole
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
                            @role('admin')
                                <i class="fas fa-user-shield fa-5x text-danger"></i>
                            @endrole
                            @role('secretaria')
                                <i class="fas fa-clipboard-check fa-5x text-info"></i>
                            @endrole
                            @role('user')
                                <i class="fas fa-user-check fa-5x text-success"></i>
                            @endrole
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
                            <div class="col-lg-3 col-md-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><i class="fas fa-trophy"></i></h3>
                                        <p>Crear Competencia</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <a href="{{ route('competitions.create') }}" class="small-box-footer">
                                        Crear <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endrole

                        {{-- Acciones para Secretaria --}}
                        @role('secretaria')
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
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><i class="fas fa-trophy"></i></h3>
                                        <p>Crear Competencia</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <a href="{{ route('competitions.create') }}" class="small-box-footer">
                                        Crear <i class="fas fa-arrow-circle-right"></i>
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
                            <div class="col-lg-3 col-md-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3><i class="fas fa-trophy"></i></h3>
                                        <p>Competencias</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-medal"></i>
                                    </div>
                                    <a href="{{ route('competitions.index') }}" class="small-box-footer">
                                        Explorar <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3><i class="fas fa-users"></i></h3>
                                        <p>Mis Equipos</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-people-group"></i>
                                    </div>
                                    <a href="{{ route('competitions.teams') }}" class="small-box-footer">
                                        Ver Equipos <i class="fas fa-arrow-circle-right"></i>
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

    {{-- Información del sistema y notificaciones --}}
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
                        Notificaciones y Recordatorios
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> ¡Bienvenido!</h5>
                        Tu cuenta está activa y lista para usar.
                    </div>
                    
                    @hasanyrole('admin|secretaria')
                        @if(isset($nextCompetitionName) && $nextCompetitionName)
                            <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-calendar"></i> Próxima Competencia</h5>
                                "{{ $nextCompetitionName }}" comienza en {{ $nextCompetitionDays }} día(s).
                            </div>
                        @endif
                        
                        @if(isset($pendingUsers) && $pendingUsers > 0)
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-user-clock"></i> Usuarios Pendientes</h5>
                                Hay {{ $pendingUsers }} usuario(s) esperando aprobación.
                            </div>
                        @endif
                    @endhasanyrole
                    
                    @role('user')
                        @if(isset($availableCompetitions) && $availableCompetitions > 0)
                            <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-trophy"></i> Competencias Disponibles</h5>
                                Hay {{ $availableCompetitions }} competencia(s) abiertas para inscripciones.
                            </div>
                        @endif
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
            box-shadow: 0 4px 8px rgba(0,0,0,.2);
        }
        
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            margin-bottom: 1rem;
        }
        
        .badge {
            font-size: 0.75em;
        }
        
        .alert {
            border-left: 4px solid;
        }
        
        .alert-warning {
            border-left-color: #ffc107;
        }
        
        .alert-info {
            border-left-color: #17a2b8;
        }
        
        .alert-success {
            border-left-color: #28a745;
        }
        
        .small-box .icon > i {
            font-size: 70px;
        }
    </style>
@stop

@section('js')
    <script>
        // Auto dismiss certain alerts after time
        setTimeout(function() {
            $('.alert-success').not('.alert-important').fadeOut();
        }, 5000);
        
        // Add pulse animation to important metrics
        @hasanyrole('admin|secretaria')
            @if(isset($competitionsNeedingBrackets) && $competitionsNeedingBrackets > 0)
                $('.small-box.bg-info').addClass('pulse-animation');
            @endif
        @endhasanyrole
        
        console.log('Dashboard cargado correctamente para rol: {{ auth()->user()->roles->first()->name ?? "sin rol" }}');
    </script>
    
    <style>
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(0, 123, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0); }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
    </style>
@stop
