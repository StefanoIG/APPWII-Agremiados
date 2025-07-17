<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CuoteController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserTeamController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SecretariaController;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Illuminate\Support\Facades\Auth;

// Página principal
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Auth::routes();

// Ruta común post-login
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware(['auth', \App\Http\Middleware\CheckUserActive::class]);

// Panel para ADMIN
Route::middleware(['auth', \App\Http\Middleware\CheckUserActive::class, 'Spatie\Permission\Middleware\RoleMiddleware:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Panel para SECRETARIA
Route::middleware(['auth', \App\Http\Middleware\CheckUserActive::class, 'Spatie\Permission\Middleware\RoleMiddleware:secretaria'])->group(function () {
    Route::get('/secretaria', [SecretariaController::class, 'index'])->name('secretaria.dashboard');
    Route::get('/secretaria/usuarios-pendientes', [SecretariaController::class, 'usuariosPendientes'])->name('secretaria.usuarios-pendientes');
    Route::get('/secretaria/usuario/{id}', [SecretariaController::class, 'mostrarUsuario'])->name('secretaria.mostrar-usuario');
    Route::post('/secretaria/usuario/{id}/aprobar', [SecretariaController::class, 'aprobarUsuario'])->name('secretaria.aprobar-usuario');
    Route::post('/secretaria/usuario/{id}/rechazar', [SecretariaController::class, 'rechazarUsuario'])->name('secretaria.rechazar-usuario');
});

// Rutas de roles (Spatie compatible, sin bindings automáticos)
Route::middleware(['auth', \App\Http\Middleware\CheckUserActive::class, 'Spatie\Permission\Middleware\RoleMiddleware:admin'])->group(function () {
    Route::resource('roles', RolController::class); // No cambiar parámetros, usar modelo Spatie
});

// Usuarios y gestión de usuarios (admin y secretaria)
Route::middleware(['auth', \App\Http\Middleware\CheckUserActive::class, 'Spatie\Permission\Middleware\RoleMiddleware:admin|secretaria'])->group(function () {
    Route::resource('users', UserController::class);
    // Puedes agregar aquí rutas adicionales para usuarios pendientes si las necesitas.
});

// Ruta para perfil de usuario (disponible para todos los usuarios autenticados)
Route::middleware(['auth', \App\Http\Middleware\CheckUserActive::class])->group(function () {
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
    
    // Rutas para que los usuarios vean sus propios archivos
    Route::get('/mi-archivo/titulo', function () {
        $user = Auth::user();
        if (!$user->titulo_pdf) {
            abort(404);
        }
        
        $path = storage_path('app/public/' . $user->titulo_pdf);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->file($path);
    })->name('mi-archivo.titulo');
    
    Route::get('/mi-archivo/qr', function () {
        $user = Auth::user();
        if (!$user->qrpdt) {
            abort(404);
        }
        
        $path = storage_path('app/public/' . $user->qrpdt);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->file($path);
    })->name('mi-archivo.qr');
});

// Rutas protegidas para archivos (solo para secretaria y admin)
Route::middleware(['auth', \App\Http\Middleware\CheckUserActive::class, 'Spatie\Permission\Middleware\RoleMiddleware:secretaria|admin'])->group(function () {
    Route::get('/archivo/titulo/{filename}', function ($filename) {
        $path = storage_path('app/public/titulos/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->file($path);
    })->name('archivo.titulo');
    
    Route::get('/archivo/qr/{filename}', function ($filename) {
        $path = storage_path('app/public/qrs/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->file($path);
    })->name('archivo.qr');
});
