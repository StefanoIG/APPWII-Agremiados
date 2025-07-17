@extends('adminlte::page')

@section('title', 'Mi Perfil')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Mi Perfil</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Mi Perfil</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        {{-- Información Personal --}}
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"></i>
                        Información Personal
                    </h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nombre Completo</label>
                                    <input type="text" class="form-control" id="name" value="{{ Auth::user()->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="created_at">Fecha de Registro</label>
                                    <input type="text" class="form-control" id="created_at" 
                                           value="{{ Auth::user()->created_at->format('d/m/Y H:i') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Estado de la Cuenta</label>
                                    <input type="text" class="form-control" id="status" 
                                           value="{{ Auth::user()->is_active ? 'Activa' : 'Pendiente' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Roles Asignados</label>
                            <div>
                                @foreach(Auth::user()->roles as $role)
                                    <span class="badge badge-primary badge-lg mr-1">
                                        <i class="fas fa-user-tag"></i> {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Documentos --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-pdf"></i>
                        Documentos Subidos
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Título Profesional</label>
                                @if(Auth::user()->titulo_pdf)
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="Archivo subido" readonly>
                                        <div class="input-group-append">
                                            <a href="{{ route('mi-archivo.titulo') }}" 
                                               target="_blank" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <input type="text" class="form-control" value="No disponible" readonly>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Código QR</label>
                                @if(Auth::user()->qrpdt)
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="Archivo subido" readonly>
                                        <div class="input-group-append">
                                            <a href="{{ route('mi-archivo.qr') }}" 
                                               target="_blank" class="btn btn-outline-secondary">
                                                <i class="fas fa-qrcode"></i> Ver
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <input type="text" class="form-control" value="No disponible" readonly>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel lateral --}}
        <div class="col-md-4">
            {{-- Avatar y estadísticas --}}
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{ asset('vendor/adminlte/dist/img/avatar5.png') }}"
                             alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>

                    <p class="text-muted text-center">
                        @foreach(Auth::user()->roles as $role)
                            {{ ucfirst($role->name) }}
                        @endforeach
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Estado</b>
                            <span class="float-right">
                                @if(Auth::user()->is_active)
                                    <span class="badge badge-success">Activa</span>
                                @else
                                    <span class="badge badge-warning">Pendiente</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Miembro desde</b>
                            <span class="float-right">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Última conexión</b>
                            <span class="float-right">{{ now()->format('d/m/Y') }}</span>
                        </li>
                    </ul>

                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#changePasswordModal">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </button>
                </div>
            </div>

            {{-- Actividad reciente --}}
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Actividad Reciente</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="time-label">
                            <span class="bg-green">{{ now()->format('d M Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-sign-in-alt bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ now()->format('H:i') }}</span>
                                <h3 class="timeline-header">Inicio de sesión</h3>
                                <div class="timeline-body">
                                    Has iniciado sesión en el sistema
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->created_at->isToday())
                            <div>
                                <i class="fas fa-user-plus bg-green"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ Auth::user()->created_at->format('H:i') }}</span>
                                    <h3 class="timeline-header">Cuenta creada</h3>
                                    <div class="timeline-body">
                                        Tu cuenta fue registrada exitosamente
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para cambiar contraseña --}}
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cambiar Contraseña</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="changePasswordForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="current_password">Contraseña Actual</label>
                            <input type="password" class="form-control" id="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="confirm_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .profile-user-img {
            width: 100px;
            height: 100px;
            border: 3px solid #adb5bd;
        }
        
        .badge-lg {
            font-size: 0.9em;
            padding: 0.5em 0.8em;
        }
        
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            left: 25px;
            height: 100%;
            width: 2px;
            background: #dee2e6;
        }
        
        .timeline > div {
            position: relative;
            margin-bottom: 15px;
        }
        
        .timeline > div > .fas {
            position: absolute;
            left: 18px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            text-align: center;
            font-size: 10px;
            line-height: 15px;
            color: #fff;
        }
        
        .timeline-item {
            background: #fff;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            margin-left: 45px;
            padding: 10px;
        }
        
        .time-label > span {
            border-radius: 4px;
            color: #fff;
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            margin-left: 45px;
            padding: 2px 5px;
            text-transform: uppercase;
        }
    </style>
@stop

@section('js')
    <script>
        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();
            
            if (newPassword !== confirmPassword) {
                alert('Las contraseñas no coinciden');
                return;
            }
            
            if (newPassword.length < 8) {
                alert('La contraseña debe tener al menos 8 caracteres');
                return;
            }
            
            // Aquí iría la lógica para cambiar la contraseña
            alert('Funcionalidad de cambio de contraseña pendiente de implementar');
            $('#changePasswordModal').modal('hide');
        });
    </script>
@stop
