@extends('adminlte::auth.register')

@section('auth_header', 'Registrar Nueva Cuenta')

@section('auth_body')
    <p class="login-box-msg">Complete todos los campos para crear su cuenta</p>

    <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
        @csrf

        {{-- Name field --}}
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="Nombre completo" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Correo electrónico" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="Contraseña" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password confirmation field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="Confirmar contraseña" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        {{-- Additional fields section --}}
        <div class="card card-outline card-info mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-pdf"></i> Documentos Requeridos
                </h3>
            </div>
            <div class="card-body">
                {{-- Título PDF field --}}
                <div class="form-group">
                    <label for="titulo_pdf">
                        <i class="fas fa-certificate"></i> Título Profesional (PDF)
                    </label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('titulo_pdf') is-invalid @enderror" 
                                   id="titulo_pdf" name="titulo_pdf" accept="application/pdf" required>
                            <label class="custom-file-label" for="titulo_pdf">Seleccionar archivo PDF...</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Suba una copia de su título profesional en formato PDF (máx. 2MB)
                    </small>
                    @error('titulo_pdf')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- QR PDF field --}}
                <div class="form-group">
                    <label for="qr_pdf">
                        <i class="fas fa-qrcode"></i> Código QR del Título (PDF)
                    </label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('qr_pdf') is-invalid @enderror" 
                                   id="qr_pdf" name="qr_pdf" accept="application/pdf" required>
                            <label class="custom-file-label" for="qr_pdf">Seleccionar archivo PDF...</label>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i> Suba el código QR de verificación de su título en formato PDF (máx. 2MB)
                    </small>
                    @error('qr_pdf')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Terms and conditions --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="icheck-primary">
                    <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                    <label for="agreeTerms">
                        Acepto los <a href="#" data-toggle="modal" data-target="#termsModal">términos y condiciones</a>
                    </label>
                </div>
            </div>
        </div>

        {{-- Register button --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Registrar Cuenta
                </button>
            </div>
        </div>
    </form>

    {{-- Terms Modal --}}
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Términos y Condiciones</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Proceso de Registro</h5>
                        Su cuenta será revisada por nuestro equipo administrativo antes de ser activada.
                    </div>
                    <h6>Al registrarse, usted acepta:</h6>
                    <ul>
                        <li>Proporcionar información veraz y actualizada</li>
                        <li>Subir documentos legítimos y no falsificados</li>
                        <li>Esperar la aprobación de su cuenta por parte de la administración</li>
                        <li>Cumplir con las políticas de la plataforma</li>
                        <li>Mantener la confidencialidad de sus credenciales</li>
                    </ul>
                    <p><strong>Nota:</strong> Una vez enviada su solicitud, recibirá una notificación por correo electrónico sobre el estado de su cuenta.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('auth_footer')
    <p class="mb-0">
        <a href="{{ route('login') }}" class="text-center">Ya tengo una cuenta</a>
    </p>
@stop

@section('adminlte_css')
    <style>
        .register-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: transparent;
            border-bottom: none;
            text-align: center;
        }
        
        .card-header h1 {
            color: #495057;
            font-weight: 300;
            margin-bottom: 0;
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 1px solid #ced4da;
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
            padding: 12px 25px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0, 0, 0, 0.1);
        }
        
        .card-outline.card-info {
            border-top-color: #667eea;
        }
        
        .custom-file-label::after {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .register-card-body {
            padding: 2rem;
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
        });
    </script>
@stop
