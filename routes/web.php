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
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CompetitionController;
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
    
    // Gestión de planes de suscripción para admin
    Route::post('/admin/planes', [SubscriptionController::class, 'storePlan'])->name('admin.plans.store');
    Route::put('/admin/planes/{plan}', [SubscriptionController::class, 'updatePlan'])->name('admin.plans.update');
    Route::patch('/admin/planes/{plan}/toggle', [SubscriptionController::class, 'togglePlan'])->name('admin.plans.toggle');
    Route::delete('/admin/planes/{plan}', [SubscriptionController::class, 'deletePlan'])->name('admin.planes.delete');
    
    // Reportes de suscripciones
    Route::get('/admin/suscripciones', [SubscriptionController::class, 'adminIndex'])->name('admin.suscripciones');
    Route::get('/admin/reportes/suscripciones', [SubscriptionController::class, 'reports'])->name('admin.reportes.suscripciones');
    
    // Aprobación de competencias (solo admin)
    Route::post('/competencias/{competition}/aprobar', [CompetitionController::class, 'approve'])->name('competitions.approve');
    Route::post('/competencias/{competition}/rechazar', [CompetitionController::class, 'reject'])->name('competitions.reject');
});

// Panel para SECRETARIA
Route::middleware(['auth', \App\Http\Middleware\CheckUserActive::class, 'Spatie\Permission\Middleware\RoleMiddleware:secretaria'])->group(function () {
    Route::get('/secretaria', [SecretariaController::class, 'index'])->name('secretaria.dashboard');
    Route::get('/secretaria/usuarios-pendientes', [SecretariaController::class, 'usuariosPendientes'])->name('secretaria.usuarios-pendientes');
    Route::get('/secretaria/usuario/{id}', [SecretariaController::class, 'mostrarUsuario'])->name('secretaria.mostrar-usuario');
    Route::post('/secretaria/usuario/{id}/aprobar', [SecretariaController::class, 'aprobarUsuario'])->name('secretaria.aprobar-usuario');
    Route::post('/secretaria/usuario/{id}/rechazar', [SecretariaController::class, 'rechazarUsuario'])->name('secretaria.rechazar-usuario');
    
    // Gestión de pagos para secretaria
    Route::get('/secretaria/pagos-pendientes', [PaymentController::class, 'pending'])->name('secretaria.pagos-pendientes');
    Route::get('/secretaria/pago/{receipt}', [PaymentController::class, 'show'])->name('secretaria.mostrar-pago');
    Route::post('/secretaria/pago/{receipt}/aprobar', [PaymentController::class, 'approve'])->name('secretaria.aprobar-pago');
    Route::post('/secretaria/pago/{receipt}/rechazar', [PaymentController::class, 'reject'])->name('secretaria.rechazar-pago');
    
    // Gestión de planes de suscripción para secretaria
    Route::post('/secretaria/planes', [SubscriptionController::class, 'storePlan'])->name('secretaria.plans.store');
    Route::put('/secretaria/planes/{plan}', [SubscriptionController::class, 'updatePlan'])->name('secretaria.plans.update');
    Route::patch('/secretaria/planes/{plan}/toggle', [SubscriptionController::class, 'togglePlan'])->name('secretaria.plans.toggle');
});

// Rutas de pagos para admin y secretaria
Route::middleware(['auth', \App\Http\Middleware\CheckUserActive::class, 'Spatie\Permission\Middleware\RoleMiddleware:admin|secretaria'])->group(function () {
    Route::get('/pagos/pendientes', [PaymentController::class, 'pending'])->name('payments.pending');
    Route::get('/pago/{receipt}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/pago/{receipt}/aprobar', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::post('/pago/{receipt}/rechazar', [PaymentController::class, 'reject'])->name('payments.reject');
    Route::get('/pago/{receipt}/ver', [PaymentController::class, 'viewReceipt'])->name('payments.view');
    Route::get('/pagos/estadisticas', [PaymentController::class, 'stats'])->name('payments.stats');
    
    // Rutas de competencias para admin y secretaria
    Route::get('/competencias/crear', [App\Http\Controllers\CompetitionController::class, 'create'])->name('competitions.create');
    Route::post('/competencias', [App\Http\Controllers\CompetitionController::class, 'store'])->name('competitions.store');
    Route::get('/competencias/{competition}/editar', [App\Http\Controllers\CompetitionController::class, 'edit'])->name('competitions.edit');
    Route::put('/competencias/{competition}', [App\Http\Controllers\CompetitionController::class, 'update'])->name('competitions.update');
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

    // Rutas del sistema de suscripciones para usuarios regulares
    Route::get('/suscripciones', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/suscripciones/planes', [SubscriptionController::class, 'plans'])->name('subscriptions.plans');
    Route::get('/suscripciones/mis-suscripciones', [SubscriptionController::class, 'mySubscriptions'])->name('subscriptions.my');
    Route::post('/suscripciones/suscribirse/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::get('/suscripciones/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::post('/pagos/subir-comprobante/{subscription}', [PaymentController::class, 'upload'])->name('payments.upload');
    
    // Rutas del sistema de competencias
    Route::get('/competencias', [App\Http\Controllers\CompetitionController::class, 'index'])->name('competitions.index');
    Route::get('/competencias/mis-equipos', [App\Http\Controllers\CompetitionController::class, 'userTeams'])->name('competitions.teams');
    Route::get('/competencias/{competition}', [App\Http\Controllers\CompetitionController::class, 'show'])->name('competitions.show');
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
