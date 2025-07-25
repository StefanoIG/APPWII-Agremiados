<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $secretariaRole = Role::firstOrCreate(['name' => 'secretaria']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Crear permisos básicos si no existen
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'approve users',
            'reject users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar permisos a roles
        $adminRole->syncPermissions($permissions);
        $secretariaRole->syncPermissions(['view users', 'approve users', 'reject users']);

        // Crear usuario administrador si no existe
        $admin = User::firstOrCreate(
            ['email' => 'admin@agremiados.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Crear usuario secretaria si no existe
        $secretaria = User::firstOrCreate(
            ['email' => 'secretaria@agremiados.com'],
            [
                'name' => 'Secretaria',
                'password' => Hash::make('secretaria123'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $secretaria->assignRole('secretaria');

        // Crear usuario de prueba si no existe
        $testUser = User::firstOrCreate(
            ['email' => 'test@agremiados.com'],
            [
                'name' => 'Usuario de Prueba',
                'identification_number' => '12345678',
                'password' => Hash::make('test123'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $testUser->assignRole('user');

        $this->command->info('Roles y usuarios creados exitosamente:');
        $this->command->info('Admin: admin@agremiados.com / admin123');
        $this->command->info('Secretaria: secretaria@agremiados.com / secretaria123');
        $this->command->info('Usuario: test@agremiados.com / test123');
    }
}
