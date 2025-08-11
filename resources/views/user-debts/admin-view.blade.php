@extends('adminlte::page')

@section('title', 'Gestión de Deudas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Deudas</h1>
        <span class="badge badge-info badge-lg">Panel de Administración</span>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <h4><i class="icon fas fa-info-circle"></i> Información para Administradores</h4>
                Como administrador o secretaria, no tienes deudas personales. Utiliza las herramientas de gestión para administrar las deudas de los usuarios.
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gestión de Cortes Mensuales -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Gestión de Cortes Mensuales
                    </h3>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-calendar-plus fa-4x text-primary mb-3"></i>
                    <p>Crea y gestiona los cortes mensuales que generan las deudas para todos los usuarios.</p>
                    <div class="btn-group-vertical" style="width: 100%;">
                        <a href="{{ route('monthly-cuts.index') }}" class="btn btn-primary btn-lg mb-2">
                            <i class="fas fa-list mr-2"></i>
                            Ver Todos los Cortes
                        </a>
                        <a href="{{ route('monthly-cuts.create') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Crear Nuevo Corte
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Deudas -->
        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                        Gestión de Deudas de Usuarios
                    </h3>
                </div>
                <div class="card-body text-center">
                    <i class="fas fa-users-cog fa-4x text-warning mb-3"></i>
                    <p>Administra las deudas de todos los usuarios, aprueba pagos y gestiona estados.</p>
                    <div class="btn-group-vertical" style="width: 100%;">
                        <a href="{{ route('user-debts.admin') }}" class="btn btn-warning btn-lg mb-2">
                            <i class="fas fa-search mr-2"></i>
                            Ver Todas las Deudas
                        </a>
                        <a href="{{ route('user-debts.admin') }}?status=pending_approval" class="btn btn-danger btn-lg">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Deudas Pendientes de Aprobación
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Estadísticas del Sistema
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cortes Activos</span>
                                    <span class="info-box-number">{{ \App\Models\MonthlyCut::where('status', 'active')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Deudas Pendientes</span>
                                    <span class="info-box-number">{{ \App\Models\UserDebt::where('status', 'pending')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Deudas Vencidas</span>
                                    <span class="info-box-number">{{ \App\Models\UserDebt::where('status', 'overdue')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Deudas Pagadas</span>
                                    <span class="info-box-number">{{ \App\Models\UserDebt::where('status', 'paid')->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlaces rápidos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        Enlaces Rápidos
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('secretaria.dashboard') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Dashboard Secretaria
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ url('users') }}" class="btn btn-outline-info btn-block">
                                <i class="fas fa-users mr-2"></i>
                                Gestionar Usuarios
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('secretaria.usuarios-pendientes') }}" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-user-clock mr-2"></i>
                                Usuarios Pendientes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .card {
            border-radius: 10px;
        }
        .info-box {
            border-radius: 8px;
        }
        .btn-group-vertical .btn {
            margin-bottom: 10px;
        }
        .btn-group-vertical .btn:last-child {
            margin-bottom: 0;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Vista de administración de deudas cargada');
    </script>
@stop
