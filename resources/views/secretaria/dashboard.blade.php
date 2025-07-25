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
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ \App\Models\User::where('is_active', true)->count() }}</h3>
                    <p>Usuarios Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <a href="{{ url('users') }}" class="small-box-footer">
                    Ver todos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ \App\Models\SubscriptionPlan::where('is_active', true)->count() }}</h3>
                    <p>Planes Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
                <a href="{{ route('subscriptions.plans') }}" class="small-box-footer">
                    Gestionar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ \App\Models\PaymentReceipt::where('status', 'pending')->count() }}</h3>
                    <p>Pagos Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <a href="{{ route('payments.pending') }}" class="small-box-footer">
                    Revisar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tachometer-alt"></i> Acciones Rápidas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-clock fa-3x text-warning mb-3"></i>
                                    <h6>Usuarios Pendientes</h6>
                                    <a href="{{ route('secretaria.usuarios-pendientes') }}" class="btn btn-warning btn-block">
                                        <i class="fas fa-eye"></i> Revisar Usuarios
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-star fa-3x text-success mb-3"></i>
                                    <h6>Planes de Suscripción</h6>
                                    <a href="{{ route('subscriptions.plans') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-cogs"></i> Gestionar Planes
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card border-danger">
                                <div class="card-body text-center">
                                    <i class="fas fa-receipt fa-3x text-danger mb-3"></i>
                                    <h6>Pagos Pendientes</h6>
                                    <a href="{{ route('payments.pending') }}" class="btn btn-danger btn-block">
                                        <i class="fas fa-check"></i> Revisar Pagos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('users.index') }}" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-users"></i> Gestionar Todos los Usuarios
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('roles') }}" class="btn btn-outline-primary btn-lg btn-block">
                                <i class="fas fa-user-shield"></i> Gestionar Roles
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