@extends('adminlte::auth.login')

@section('auth_header', 'Iniciar Sesión')

@section('auth_body')
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="{{ route('login') }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Correo electrónico" required autofocus>
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

        {{-- Remember me checkbox --}}
        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">
                        Recordarme
                    </label>
                </div>
            </div>

            {{-- Login button --}}
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if (Route::has('password.request'))
        <p class="mb-1">
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        </p>
    @endif

    {{-- Register link --}}
    @if (Route::has('register'))
        <p class="mb-0">
            <a href="{{ route('register') }}" class="text-center">Registrar una nueva cuenta</a>
        </p>
    @endif
@stop

@section('adminlte_css')
    <style>
        .login-page {
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
        
        .form-control:focus {
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
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0, 0, 0, 0.1);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .login-card-body {
            padding: 2rem;
        }
    </style>
@stop
