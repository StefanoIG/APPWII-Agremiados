@extends('adminlte::page')

@section('title', 'Usuarios Pendientes')

@section('content_header')
    <h1>Usuarios Pendientes de Aprobación</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Usuarios Pendientes</h3>
        </div>
        <div class="card-body">
            @if($usuarios->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Fecha de Registro</th>
                                <th>Documentos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->id }}</td>
                                    <td>{{ $usuario->name }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>{{ $usuario->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($usuario->titulo_pdf)
                                            <a href="{{ route('archivo.titulo', basename($usuario->titulo_pdf)) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-pdf"></i> Título
                                            </a>
                                        @endif
                                        @if($usuario->qrpdt)
                                            <a href="{{ route('archivo.qr', basename($usuario->qrpdt)) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-qrcode"></i> QR
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('secretaria.mostrar-usuario', $usuario->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success" onclick="aprobarUsuario({{ $usuario->id }})">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="rechazarUsuario({{ $usuario->id }})">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $usuarios->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Información!</h5>
                    No hay usuarios pendientes de aprobación en este momento.
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para aprobar usuario -->
    <div class="modal fade" id="aprobarModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar Aprobación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas aprobar este usuario? Se le enviará un correo de confirmación.</p>
                </div>
                <div class="modal-footer">
                    <form id="aprobarForm" method="POST">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Aprobar Usuario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para rechazar usuario -->
    <div class="modal fade" id="rechazarModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Rechazar Usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="rechazarForm" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="motivo">Motivo del rechazo *</label>
                            <textarea class="form-control" id="motivo" name="motivo" rows="4" required 
                                placeholder="Explica el motivo del rechazo..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" form="rechazarForm" class="btn btn-danger">Rechazar Usuario</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table td {
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
    <script>
        function aprobarUsuario(userId) {
            $('#aprobarForm').attr('action', '/secretaria/usuario/' + userId + '/aprobar');
            $('#aprobarModal').modal('show');
        }

        function rechazarUsuario(userId) {
            $('#rechazarForm').attr('action', '/secretaria/usuario/' + userId + '/rechazar');
            $('#rechazarModal').modal('show');
        }
    </script>
@stop
