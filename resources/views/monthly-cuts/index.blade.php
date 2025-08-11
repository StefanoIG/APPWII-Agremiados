@extends('adminlte::page')

@section('title', 'Cortes Mensuales')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">📅 Gestión de Cortes Mensuales</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Cortes Mensuales</li>
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📋 Lista de Cortes Mensuales</h3>
                    <div class="card-tools">
                        <a href="{{ route('monthly-cuts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear Nuevo Corte
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($cuts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nombre del Corte</th>
                                        <th>Fecha de Corte</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Creado por</th>
                                        <th>Fecha de Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cuts as $cut)
                                        <tr>
                                            <td>
                                                <strong>{{ $cut->cut_name }}</strong>
                                                @if($cut->description)
                                                    <br><small class="text-muted">{{ Str::limit($cut->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $cut->cut_date->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge badge-info">
                                                    ${{ number_format($cut->amount, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $cut->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ $cut->status === 'active' ? 'Activo' : 'Cerrado' }}
                                                </span>
                                            </td>
                                            <td>{{ $cut->creator->name ?? 'N/A' }}</td>
                                            <td>{{ $cut->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('monthly-cuts.show', $cut) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($cut->isActive())
                                                        <a href="{{ route('monthly-cuts.edit', $cut) }}" class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('monthly-cuts.close', $cut) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-secondary" 
                                                                    onclick="return confirm('¿Estás seguro de cerrar este corte?')">
                                                                <i class="fas fa-lock"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $cuts->links() }}
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-muted">No hay cortes mensuales registrados.</p>
                            <a href="{{ route('monthly-cuts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear el Primer Corte
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
    </style>
@stop
