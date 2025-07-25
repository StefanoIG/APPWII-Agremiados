@extends('adminlte::page')

@section('title', 'Disciplinas')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Disciplinas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Disciplinas</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-dumbbell"></i>
                        Lista de Disciplinas
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('disciplinas.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nueva Disciplina
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fas fa-check"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($disciplinas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Fecha Creación</th>
                                        <th width="120px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($disciplinas as $disciplina)
                                        <tr>
                                            <td>{{ $disciplina->id }}</td>
                                            <td>
                                                <strong>{{ $disciplina->name }}</strong>
                                            </td>
                                            <td>
                                                {{ $disciplina->description ?? 'Sin descripción' }}
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $disciplina->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('disciplinas.show', $disciplina) }}" 
                                                       class="btn btn-info btn-sm" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('disciplinas.edit', $disciplina) }}" 
                                                       class="btn btn-warning btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('disciplinas.destroy', $disciplina) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                title="Eliminar" 
                                                                onclick="return confirm('¿Estás seguro de eliminar esta disciplina?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginación --}}
                        <div class="d-flex justify-content-center">
                            {{ $disciplinas->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-dumbbell fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay disciplinas registradas</h5>
                            <p class="text-muted">Crea la primera disciplina para comenzar.</p>
                            <a href="{{ route('disciplinas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Disciplina
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop
