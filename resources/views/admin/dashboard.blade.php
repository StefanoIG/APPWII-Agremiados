@extends('adminlte::page')

@section('title', 'Panel de Administrador')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Panel de Administrador</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Admin Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Estadísticas principales -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ \App\Models\User::count() }}</h3>
                    <p>Total Usuarios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ url('users') }}" class="small-box-footer">
                    Ver todos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ \App\Models\User::where('is_active', true)->count() }}</h3>
                    <p>Usuarios Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <a href="{{ url('users') }}" class="small-box-footer">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ \App\Models\SubscriptionPlan::count() }}</h3>
                    <p>Planes de Suscripción</p>
                </div>
                <div class="icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <a href="{{ route('subscriptions.plans') }}" class="small-box-footer">
                    Gestionar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ \App\Models\User::where('is_active', false)->count() }}</h3>
                    <p>Usuarios Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <a href="{{ url('users') }}" class="small-box-footer">
                    Revisar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Accesos rápidos principales -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-rocket"></i> Accesos Rápidos
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Gestión de Usuarios -->
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                    <h5>Gestión de Usuarios</h5>
                                    <p class="text-muted">Administrar usuarios, roles y permisos</p>
                                    <div class="btn-group-vertical w-100">
                                        <a href="{{ url('users') }}" class="btn btn-primary btn-sm mb-1">
                                            <i class="fas fa-list"></i> Ver Todos los Usuarios
                                        </a>
                                        <a href="{{ url('roles') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-user-shield"></i> Gestionar Roles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Suscripciones y Pagos -->
                        <div class="col-md-4 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-credit-card fa-3x text-success mb-3"></i>
                                    <h5>Suscripciones y Pagos</h5>
                                    <p class="text-muted">Gestionar planes y pagos</p>
                                    <div class="btn-group-vertical w-100">
                                        <a href="{{ route('subscriptions.plans') }}" class="btn btn-success btn-sm mb-1">
                                            <i class="fas fa-star"></i> Gestionar Planes
                                        </a>
                                        <a href="#" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-receipt"></i> Ver Pagos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sistema -->
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-cogs fa-3x text-warning mb-3"></i>
                                    <h5>Sistema</h5>
                                    <p class="text-muted">Configuración y mantenimiento</p>
                                    <div class="btn-group-vertical w-100">
                                        <a href="{{ url('categorias') }}" class="btn btn-warning btn-sm mb-1">
                                            <i class="fas fa-list"></i> Categorías
                                        </a>
                                        <a href="{{ url('disciplinas') }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-dumbbell"></i> Disciplinas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de actividad reciente -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock"></i> Usuarios Recientes
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $recentUsers = \App\Models\User::latest()->take(5)->get();
                    @endphp
                    @if($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Estado</th>
                                        <th>Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->is_active)
                                                    <span class="badge badge-success">Activo</span>
                                                @else
                                                    <span class="badge badge-warning">Pendiente</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No hay usuarios registrados.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i> Estadísticas de Suscripciones
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $totalSubscriptions = \App\Models\UserSubscription::count();
                        $activeSubscriptions = \App\Models\UserSubscription::where('status', 'active')->count();
                        $pendingSubscriptions = \App\Models\UserSubscription::where('status', 'pending')->count();
                    @endphp
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="description-block">
                                <h5 class="description-header text-primary">{{ $totalSubscriptions }}</h5>
                                <span class="description-text">Total</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="description-block">
                                <h5 class="description-header text-success">{{ $activeSubscriptions }}</h5>
                                <span class="description-text">Activas</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="description-block">
                                <h5 class="description-header text-warning">{{ $pendingSubscriptions }}</h5>
                                <span class="description-text">Pendientes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.small-box:hover {
    transform: translateY(-2px);
    transition: transform 0.2s;
}
</style>
@stop
