<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar paginación con Bootstrap 4
        \Illuminate\Pagination\Paginator::defaultView('pagination::bootstrap-4');
        \Illuminate\Pagination\Paginator::defaultSimpleView('pagination::simple-bootstrap-4');
        
        Gate::define('admin-or-secretaria', function ($user) {
            // Usar Spatie para verificar roles
            return $user->hasAnyRole(['admin', 'secretaria']);
        });
    }
}
