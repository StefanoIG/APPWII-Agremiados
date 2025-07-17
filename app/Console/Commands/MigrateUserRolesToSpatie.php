<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class MigrateUserRolesToSpatie extends Command
{
    protected $signature = 'roles:migrate-users';
    protected $description = 'Migrar los roles del campo users.role al sistema de Spatie';

    public function handle()
    {
        $this->info('Migrando roles de usuarios...');

        // Crear roles si no existen
        $roles = ['admin', 'secretaria', 'user'];
        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }

        // Asignar roles a cada usuario
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role) {
                $user->syncRoles([$user->role]);
                $this->line("Usuario {$user->email} => rol '{$user->role}' asignado");
            }
        }

        $this->info('¡Migración de roles completada!');
    }
}
