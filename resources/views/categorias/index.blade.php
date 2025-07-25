@extends('adminlte::page')

@section('title', 'Categorías')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Gestión de Categorías</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Categorías</li>
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
                        <i class="fas fa-list"></i>
                        Lista de Categorías
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('categorias.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nueva Categoría
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

                    @if($categorias->count() > 0)
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
                                    @foreach($categorias as $categoria)
                                        <tr>
                                            <td>{{ $categoria->id }}</td>
                                            <td>
                                                <strong>{{ $categoria->name }}</strong>
                                            </td>
                                            <td>
                                                {{ $categoria->description ?? 'Sin descripción' }}
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $categoria->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('categorias.show', $categoria) }}" 
                                                       class="btn btn-info btn-sm" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('categorias.edit', $categoria) }}" 
                                                       class="btn btn-warning btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            title="Eliminar" onclick="deleteCategoria({{ $categoria->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                
                                                <form id="delete-form-{{ $categoria->id }}" 
                                                      action="{{ route('categorias.destroy', $categoria) }}" 
                                                      method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginación --}}
                        <div class="d-flex justify-content-center">
                            {{ $categorias->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay categorías registradas</h5>
                            <p class="text-muted">Crea la primera categoría para comenzar.</p>
                            <a href="{{ route('categorias.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Categoría
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
function deleteCategoria(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esta acción",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@stop
