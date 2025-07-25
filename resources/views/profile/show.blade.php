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
                                    <label for="identification_number">Número de Identificación</label>
                                    <input type="text" class="form-control" id="identification_number" 
                                           value="{{ Auth::user()->identification_number ?? 'No especificado' }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Teléfono</label>
                                    <input type="text" class="form-control" id="phone" 
                                           value="{{ Auth::user()->phone ?? 'No especificado' }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birth_date">Fecha de Nacimiento</label>
                                    <input type="text" class="form-control" id="birth_date" 
                                           value="{{ Auth::user()->birth_date ? Auth::user()->birth_date->format('d/m/Y') : 'No especificado' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Género</label>
                                    <input type="text" class="form-control" id="gender" 
                                           value="{{ Auth::user()->gender ? (Auth::user()->gender == 'M' ? 'Masculino' : (Auth::user()->gender == 'F' ? 'Femenino' : Auth::user()->gender)) : 'No especificado' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Dirección</label>
                                    <textarea class="form-control" id="address" rows="2" readonly>{{ Auth::user()->address ?? 'No especificado' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profession">Profesión</label>
                                    <input type="text" class="form-control" id="profession" 
                                           value="{{ Auth::user()->profession ?? 'No especificado' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="created_at">Fecha de Registro</label>
                                    <input type="text" class="form-control" id="created_at" 
                                           value="{{ Auth::user()->created_at->format('d/m/Y H:i') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact_name">Contacto de Emergencia</label>
                                    <input type="text" class="form-control" id="emergency_contact_name" 
                                           value="{{ Auth::user()->emergency_contact_name ?? 'No especificado' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="emergency_contact_phone">Teléfono de Emergencia</label>
                                    <input type="text" class="form-control" id="emergency_contact_phone" 
                                           value="{{ Auth::user()->emergency_contact_phone ?? 'No especificado' }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Estado de la Cuenta</label>
                                    <input type="text" class="form-control" id="status" 
                                           value="{{ Auth::user()->is_active ? 'Activa' : 'Pendiente' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-info-circle"></i>
                                        <small>Funcionalidad QR pendiente de implementación</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Suscripciones --}}
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-credit-card"></i>
                        Mi Suscripción
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        // Simulamos una suscripción activa para mostrar la interfaz
                        $hasActiveSubscription = false; // Auth::user()->hasActiveSubscription()
                        $activeSubscription = null; // Auth::user()->activeSubscription()
                    @endphp
                    
                    @if($hasActiveSubscription && $activeSubscription)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Plan Actual</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $activeSubscription->subscriptionPlan->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <input type="text" class="form-control" 
                                           value="{{ ucfirst($activeSubscription->status) }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Inicio</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $activeSubscription->start_date->format('d/m/Y') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Vencimiento</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $activeSubscription->end_date->format('d/m/Y') }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fas fa-history"></i> Ver Historial de Pagos
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>No tienes una suscripción activa</strong><br>
                                Para acceder a todas las funcionalidades, necesitas activar un plan de suscripción.
                            </div>
                            
                            @if(Auth::user()->is_active)
                                <a href="#" class="btn btn-success btn-lg">
                                    <i class="fas fa-shopping-cart"></i> Ver Planes de Suscripción
                                </a>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-clock"></i>
                                    <strong>Cuenta pendiente de aprobación</strong><br>
                                    Una vez que tu cuenta sea aprobada, podrás seleccionar un plan de suscripción.
                                </div>
                            @endif
                        </div>
                    @endif
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
                            <b>Identificación</b>
                            <span class="float-right">{{ Auth::user()->identification_number ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Profesión</b>
                            <span class="float-right">{{ Str::limit(Auth::user()->profession ?? 'N/A', 20) }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Miembro desde</b>
                            <span class="float-right">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Suscripción</b>
                            <span class="float-right">
                                @if(Auth::user()->hasActiveSubscription())
                                    <span class="badge badge-success">Activa</span>
                                @else
                                    <span class="badge badge-secondary">Sin plan</span>
                                @endif
                            </span>
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
            <!-- Información de Suscripción -->
            <div class="col-md-12 mt-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-star"></i> Estado de Suscripción
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(Auth::user()->hasActiveSubscription())
                            @php
                                $activeSubscription = Auth::user()->activeSubscription();
                            @endphp
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong><i class="fas fa-credit-card mr-1"></i> Plan Actual</strong>
                                    <p class="text-muted">{{ $activeSubscription->plan->name }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <strong><i class="fas fa-calendar mr-1"></i> Válido hasta</strong>
                                    <p class="text-muted">{{ $activeSubscription->ends_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <strong><i class="fas fa-clock mr-1"></i> Días restantes</strong>
                                    @php
                                        $daysRemaining = $activeSubscription->ends_at->diffInDays(now());
                                    @endphp
                                    <p class="text-muted">
                                        <span class="badge badge-{{ $daysRemaining > 30 ? 'success' : ($daysRemaining > 7 ? 'warning' : 'danger') }}">
                                            {{ $daysRemaining }} días
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('subscriptions.my') }}" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> Ver Mis Suscripciones
                                    </a>
                                    <a href="{{ route('subscriptions.plans') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-star"></i> Ver Otros Planes
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-exclamation-triangle"></i> Sin Suscripción Activa</h5>
                                <p>Para acceder a todas las funcionalidades del sistema, necesitas una suscripción activa.</p>
                                <a href="{{ route('subscriptions.plans') }}" class="btn btn-primary">
                                    <i class="fas fa-star"></i> Ver Planes Disponibles
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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
