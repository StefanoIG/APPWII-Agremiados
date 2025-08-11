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
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $pendingDebtsCount }}</h3>
                    <p>Deudas Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('user-debts.index') }}" class="small-box-footer">
                    Ver deudas <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ \App\Models\MonthlyCut::count() }}</h3>
                    <p>Cortes Mensuales</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="{{ route('monthly-cuts.index') }}" class="small-box-footer">
                    Gestionar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Información del corte mensual activo -->
    @if($activeMonthlycut)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Corte Mensual Activo
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Período:</strong> {{ $activeMonthlycut->name }}<br>
                            <strong>Monto:</strong> ${{ number_format($activeMonthlycut->amount, 2) }}<br>
                            <strong>Fecha de corte:</strong> {{ $activeMonthlycut->cut_date->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Estado:</strong> 
                            <span class="badge badge-success">{{ ucfirst($activeMonthlycut->status) }}</span><br>
                            <strong>Creado por:</strong> {{ $activeMonthlycut->creator->name }}<br>
                            <strong>Descripción:</strong> {{ $activeMonthlycut->description ?? 'Sin descripción' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Información!</h5>
                No hay cortes mensuales activos. <a href="{{ route('monthly-cuts.create') }}">Crear nuevo corte</a>
            </div>
        </div>
    </div>
    @endif

    <!-- Deudas vencidas -->
    @if($overdueDebtsCount > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Atención!</h5>
                Hay {{ $overdueDebtsCount }} deudas vencidas que requieren atención. 
                <a href="{{ route('user-debts.index', ['status' => 'overdue']) }}">Ver deudas vencidas</a>
            </div>
        </div>
    </div>
    @endif

    <!-- Acciones rápidas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt mr-2"></i>
                        Acciones Rápidas
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('monthly-cuts.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus mr-2"></i>
                                Crear Nuevo Corte
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('user-debts.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-search mr-2"></i>
                                Ver Todas las Deudas
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('secretaria.usuarios-pendientes') }}" class="btn btn-info btn-block">
                                <i class="fas fa-user-check mr-2"></i>
                                Aprobar Usuarios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($pendingUsersCount > 0)
        <div class="alert alert-warning mt-4">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Atención!</h5>
            Tienes {{ $pendingUsersCount }} usuario(s) pendiente(s) de revisión.
        </div>
    @endif
@stop

@section('css')
    <style>
        .small-box .icon {
            font-size: 60px;
        }
        .alert {
            border-radius: 8px;
        }
    </style>
@stop

@section('js')
    <script> console.log('Dashboard de Secretaria cargado con sistema de deudas'); </script>
@stop
