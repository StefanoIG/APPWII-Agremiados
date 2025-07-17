<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndSecretariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles si no existen
        $roles = ['admin', 'secretaria', 'user'];
        
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Crear usuario secretaria si no existe
        $secretaria = User::where('email', 'secretaria@agremiados.com')->first();
        
        if (!$secretaria) {
            $secretaria = User::create([
                'name' => 'Secretaria Sistema',
                'email' => 'secretaria@agremiados.com',
                'password' => Hash::make('password123'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $secretaria->assignRole('secretaria');
            
            $this->command->info('Usuario secretaria creado: secretaria@agremiados.com / password123');
        }

        // Crear usuario admin si no existe
        $admin = User::where('email', 'admin@agremiados.com')->first();
        
        if (!$admin) {
            $admin = User::create([
                'name' => 'Administrador Sistema',
                'email' => 'admin@agremiados.com',
                'password' => Hash::make('admin123'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $admin->assignRole('admin');
            
            $this->command->info('Usuario admin creado: admin@agremiados.com / admin123');
        }
    }
}
