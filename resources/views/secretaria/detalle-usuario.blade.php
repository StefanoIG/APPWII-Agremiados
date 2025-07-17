@extends('adminlte::page')

@section('title', 'Detalle de Usuario')

@section('content_header')
    <h1>Detalle del Usuario</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información del Usuario</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">ID:</th>
                            <td>{{ $usuario->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td>{{ $usuario->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $usuario->email }}</td>
                        </tr>
                        <tr>
                            <th>Estado:</th>
                            <td>
                                @if($usuario->is_active)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-warning">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Fecha de Registro:</th>
                            <td>{{ $usuario->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        @if($usuario->updated_at && $usuario->updated_at != $usuario->created_at)
                        <tr>
                            <th>Última Actualización:</th>
                            <td>{{ $usuario->updated_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Roles:</th>
                            <td>
                                @foreach($usuario->getRoleNames() as $role)
                                    <span class="badge badge-info">{{ $role }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Documentos</h3>
                </div>
                <div class="card-body">
                    @if($usuario->titulo_pdf || $usuario->qrpdt)
                        <div class="row">
                            @if($usuario->titulo_pdf)
                                <div class="col-12 mb-3">
                                    <h6>Título PDF:</h6>
                                    <a href="{{ route('archivo.titulo', basename($usuario->titulo_pdf)) }}" target="_blank" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-file-pdf"></i> Ver Título
                                    </a>
                                </div>
                            @endif
                            
                            @if($usuario->qrpdt)
                                <div class="col-12 mb-3">
                                    <h6>QR PDF:</h6>
                                    <a href="{{ route('archivo.qr', basename($usuario->qrpdt)) }}" target="_blank" class="btn btn-outline-secondary btn-block">
                                        <i class="fas fa-qrcode"></i> Ver QR
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No hay documentos adjuntos.</p>
                    @endif
                </div>
            </div>
            
            @if(!$usuario->is_active)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Acciones</h3>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success btn-block mb-2" onclick="aprobarUsuario({{ $usuario->id }})">
                            <i class="fas fa-check"></i> Aprobar Usuario
                        </button>
                        <button type="button" class="btn btn-danger btn-block" onclick="rechazarUsuario({{ $usuario->id }})">
                            <i class="fas fa-times"></i> Rechazar Usuario
                        </button>
                    </div>
                </div>
            @else
                <div class="alert alert-success">
                    <h5><i class="icon fas fa-check"></i> Usuario Aprobado!</h5>
                    Este usuario ya ha sido aprobado y puede acceder al sistema.
                </div>
            @endif
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <a href="{{ route('secretaria.usuarios-pendientes') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a la Lista
            </a>
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
                    <p>¿Estás seguro de que deseas aprobar a <strong>{{ $usuario->name }}</strong>?</p>
                    <p>Se le enviará un correo de confirmación automáticamente.</p>
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
                    <p>¿Estás seguro de que deseas rechazar a <strong>{{ $usuario->name }}</strong>?</p>
                    <p class="text-danger">Esta acción eliminará al usuario del sistema permanentemente.</p>
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
