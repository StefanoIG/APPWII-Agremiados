@extends('adminlte::auth.register')

@section('auth_header', 'Registro de Nuevo Agremiado')

@section('auth_body')
    <p class="login-box-msg">Complete todos los campos para crear su cuenta</p>

    <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row">
            {{-- Columna izquierda --}}
            <div class="col-md-6">
                {{-- Nombre completo --}}
                <div class="form-group">
                    <label for="name" class="form-label">
                        <i class="fas fa-user text-primary"></i> Nombre Completo
                    </label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Ingrese su nombre completo" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Número de identificación --}}
                <div class="form-group">
                    <label for="identification_number" class="form-label">
                        <i class="fas fa-id-card text-primary"></i> Número de Identificación
                    </label>
                    <input type="text" name="identification_number" id="identification_number" 
                           class="form-control @error('identification_number') is-invalid @enderror"
                           value="{{ old('identification_number') }}" placeholder="CC, TI, CE, etc." required>
                    @error('identification_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope text-primary"></i> Correo Electrónico
                    </label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="ejemplo@email.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div class="form-group">
                    <label for="phone" class="form-label">
                        <i class="fas fa-phone text-primary"></i> Teléfono
                    </label>
                    <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}" placeholder="+57 300 123 4567" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Fecha de nacimiento --}}
                <div class="form-group">
                    <label for="birth_date" class="form-label">
                        <i class="fas fa-calendar text-primary"></i> Fecha de Nacimiento
                    </label>
                    <input type="date" name="birth_date" id="birth_date" 
                           class="form-control @error('birth_date') is-invalid @enderror"
                           value="{{ old('birth_date') }}" required>
                    @error('birth_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Género --}}
                <div class="form-group">
                    <label for="gender" class="form-label">
                        <i class="fas fa-venus-mars text-primary"></i> Género
                    </label>
                    <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                        <option value="">Seleccione...</option>
                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Femenino</option>
                        <option value="Otro" {{ old('gender') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Columna derecha --}}
            <div class="col-md-6">
                {{-- Dirección --}}
                <div class="form-group">
                    <label for="address" class="form-label">
                        <i class="fas fa-map-marker-alt text-primary"></i> Dirección de Residencia
                    </label>
                    <textarea name="address" id="address" rows="2" 
                              class="form-control @error('address') is-invalid @enderror"
                              placeholder="Calle, carrera, número, ciudad" required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Profesión --}}
                <div class="form-group">
                    <label for="profession" class="form-label">
                        <i class="fas fa-briefcase text-primary"></i> Profesión u Ocupación
                    </label>
                    <input type="text" name="profession" id="profession" 
                           class="form-control @error('profession') is-invalid @enderror"
                           value="{{ old('profession') }}" placeholder="Ingrese su profesión" required>
                    @error('profession')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Contacto de emergencia - Nombre --}}
                <div class="form-group">
                    <label for="emergency_contact_name" class="form-label">
                        <i class="fas fa-user-friends text-primary"></i> Contacto de Emergencia - Nombre
                    </label>
                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" 
                           class="form-control @error('emergency_contact_name') is-invalid @enderror"
                           value="{{ old('emergency_contact_name') }}" placeholder="Nombre del contacto" required>
                    @error('emergency_contact_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Contacto de emergencia - Teléfono --}}
                <div class="form-group">
                    <label for="emergency_contact_phone" class="form-label">
                        <i class="fas fa-phone-alt text-primary"></i> Contacto de Emergencia - Teléfono
                    </label>
                    <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" 
                           class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                           value="{{ old('emergency_contact_phone') }}" placeholder="+57 300 123 4567" required>
                    @error('emergency_contact_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Contraseña --}}
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock text-primary"></i> Contraseña
                    </label>
                    <input type="password" name="password" id="password" 
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Mínimo 8 caracteres" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirmar contraseña --}}
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock text-primary"></i> Confirmar Contraseña
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="form-control" placeholder="Repita la contraseña" required>
                </div>
            </div>
        </div>

        {{-- Documentos --}}
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="text-primary">
                    <i class="fas fa-file-upload"></i> Documentos Requeridos
                </h5>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Por favor adjunte los siguientes documentos para completar su registro:
                </div>
            </div>

            {{-- Título profesional --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="titulo_pdf" class="form-label">
                        <i class="fas fa-graduation-cap text-primary"></i> Título Profesional (PDF)
                    </label>
                    <div class="custom-file">
                        <input type="file" name="titulo_pdf" id="titulo_pdf" 
                               class="custom-file-input @error('titulo_pdf') is-invalid @enderror"
                               accept=".pdf" required>
                        <label class="custom-file-label" for="titulo_pdf">Seleccionar archivo PDF...</label>
                        @error('titulo_pdf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">Formato: PDF, Tamaño máximo: 2MB</small>
                </div>
            </div>

            {{-- Comentario sobre QR (removido por ahora) --}}
            <div class="col-md-6">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Nota:</strong> La verificación QR será habilitada en una futura actualización.
                </div>
            </div>
        </div>

        {{-- Términos y condiciones --}}
        <div class="row mt-3">
            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" name="agree_terms" id="agreeTerms" class="form-check-input" required>
                    <label class="form-check-label" for="agreeTerms">
                        Acepto los <a href="#" class="text-primary">términos y condiciones</a> y 
                        la <a href="#" class="text-primary">política de privacidad</a>
                    </label>
                </div>
            </div>
        </div>

        {{-- Botón de registro --}}
        <div class="row mt-4">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <i class="fas fa-user-plus"></i> Registrar Cuenta
                </button>
            </div>
        </div>
    </form>

    <div class="text-center mt-3">
        <p class="mb-0">
            ¿Ya tienes una cuenta? 
            <a href="{{ route('login') }}" class="text-primary">Iniciar Sesión</a>
        </p>
    </div>
@stop

@section('adminlte_css')
    <style>
        .register-box {
            width: 90%;
            max-width: 1000px;
            margin: 7% auto;
        }
        
        .card {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 2rem;
        }
        
        .card-header h1 {
            color: white;
            font-weight: 300;
            margin-bottom: 0;
            font-size: 1.8rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .custom-file-label {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus, .custom-file-input:focus ~ .custom-file-label {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 25px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0, 0, 0, 0.1);
        }
        
        .custom-file-label::after {
            background: #667eea;
            color: white;
            border-color: #667eea;
            border-radius: 0 6px 6px 0;
        }
        
        .register-card-body {
            padding: 2rem;
            background: #fafafa;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .alert-info {
            background: #e3f2fd;
            color: #0277bd;
        }
        
        .alert-warning {
            background: #fff3e0;
            color: #ef6c00;
        }
        
        .text-primary {
            color: #667eea !important;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .login-box-msg {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 768px) {
            .register-box {
                width: 95%;
                margin: 3% auto;
            }
            
            .card-header {
                padding: 1.5rem;
            }
            
            .register-card-body {
                padding: 1.5rem;
            }
        }
    </style>
@stop

@section('adminlte_js')
    <script>
        // Custom file input label update
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
        
        // Form validation
        $('form').on('submit', function(e) {
            if (!$('#agreeTerms').is(':checked')) {
                e.preventDefault();
                alert('Debe aceptar los términos y condiciones para continuar.');
                return false;
            }
            
            // Validar contraseñas
            const password = $('#password').val();
            const confirmPassword = $('#password_confirmation').val();
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden.');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 8 caracteres.');
                return false;
            }
        });
        
        // Formatear números de teléfono
        $('input[type="tel"]').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 0) {
                if (!value.startsWith('57')) {
                    value = '57' + value;
                }
                $(this).val('+' + value);
            }
        });
    </script>
@stop
