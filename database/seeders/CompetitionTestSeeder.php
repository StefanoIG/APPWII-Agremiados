<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Competition;
use App\Models\CompetitionTeam;
use App\Models\CompetitionTeamMember;
use App\Models\Categoria;
use App\Models\Disciplina;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CompetitionTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar datos existentes de prueba
        $this->command->info('🧹 Limpiando datos de prueba existentes...');
        
        // Eliminar competencia test anterior si existe
        $existingCompetition = Competition::where('name', 'Competicion Test 3')->first();
        if ($existingCompetition) {
            // Eliminar membresías de equipos
            CompetitionTeamMember::whereHas('team', function($query) use ($existingCompetition) {
                $query->where('competition_id', $existingCompetition->id);
            })->delete();
            
            // Eliminar equipos
            CompetitionTeam::where('competition_id', $existingCompetition->id)->delete();
            
            // Eliminar competencia
            $existingCompetition->delete();
        }
        
        // Eliminar usuarios de prueba existentes
        $testUsers = User::where('email', 'like', 'test%@example.com')->get();
        foreach ($testUsers as $user) {
            UserSubscription::where('user_id', $user->id)->delete();
            $user->delete();
        }
        
        // Eliminar plan de prueba si existe
        $existingPlan = SubscriptionPlan::where('name', 'Plan Básico Test')->first();
        if ($existingPlan) {
            $existingPlan->delete();
        }
        // Obtener o crear categoría Mixto
        $categoria = Categoria::firstOrCreate([
            'name' => 'Mixto'
        ], [
            'description' => 'Categoría mixta para competencias',
            'is_active' => true
        ]);

        // Obtener o crear disciplina Fútbol
        $disciplina = Disciplina::firstOrCreate([
            'name' => 'Fútbol'
        ], [
            'description' => 'Deporte de fútbol',
            'is_active' => true
        ]);

        // Obtener usuario secretaria (asumiendo que existe)
        $secretaria = User::role('secretaria')->first();
        if (!$secretaria) {
            // Crear usuario secretaria si no existe
            $secretaria = User::create([
                'name' => 'Usuario Secretaria',
                'email' => 'secretaria@test.com',
                'identification_number' => '12345678',
                'phone' => '1234567890',
                'birth_date' => '1990-01-01',
                'address' => 'Dirección de prueba',
                'gender' => 'F',
                'emergency_contact_name' => 'Contacto Emergencia',
                'emergency_contact_phone' => '0987654321',
                'profession' => 'Administradora',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $secretaria->assignRole('secretaria');
        }

        // Crear competencia test 3
        $competition = Competition::create([
            'name' => 'Competicion Test 3',
            'description' => 'Competencia de prueba para testing del sistema de equipos',
            'categoria_id' => $categoria->id,
            'disciplina_id' => $disciplina->id,
            'members_per_team' => 11,
            'min_members' => 11,
            'max_members' => 15,
            'max_teams' => 20,
            'start_date' => '2025-07-31',
            'end_date' => '2025-08-24',
            'registration_deadline' => '2025-07-30',
            'status' => 'open',
            'created_by' => $secretaria->id,
            'approved_by' => $secretaria->id,
            'approved_at' => now(),
        ]);

        // Obtener o crear plan de suscripción para los usuarios
        $plan = SubscriptionPlan::firstOrCreate([
            'name' => 'Plan Básico Test'
        ], [
            'description' => 'Plan básico para testing',
            'type' => 'monthly',
            'price' => 50.00,
            'duration_months' => 12,
            'is_active' => true
        ]);

        // Crear 300 usuarios de prueba (15 miembros * 20 equipos)
        $users = [];
        for ($i = 1; $i <= 300; $i++) {
            $user = User::create([
                'name' => "Test User {$i}",
                'email' => "test{$i}@example.com",
                'identification_number' => str_pad($i, 8, '0', STR_PAD_LEFT),
                'phone' => '123456' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'birth_date' => Carbon::now()->subYears(rand(18, 35))->format('Y-m-d'),
                'address' => "Dirección de prueba {$i}",
                'gender' => $i % 2 == 0 ? 'M' : 'F',
                'emergency_contact_name' => "Contacto {$i}",
                'emergency_contact_phone' => '098765' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'profession' => 'Deportista',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Crear suscripción activa para cada usuario
            UserSubscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'start_date' => Carbon::now()->subMonth(),
                'end_date' => Carbon::now()->addMonths(11),
                'status' => 'active',
                'amount_paid' => $plan->price,
                'notes' => 'Suscripción de prueba para testing',
            ]);

            $users[] = $user;
        }

        // Crear 20 equipos con nombres creativos
        $teamNames = [
            'Los Rompecucos', 'Águilas Doradas', 'Tigres Salvajes', 'Leones FC',
            'Dragones de Fuego', 'Halcones Negros', 'Lobos Grises', 'Panteras Azules',
            'Escorpiones', 'Vikingos FC', 'Gladiadores', 'Espartanos',
            'Titanes', 'Guerreros', 'Campeones', 'Invencibles',
            'Thunders FC', 'Lightning Team', 'Storm Riders', 'Fire United'
        ];

        $userIndex = 0;
        
        for ($teamIndex = 0; $teamIndex < 20; $teamIndex++) {
            // Crear equipo
            $team = CompetitionTeam::create([
                'competition_id' => $competition->id,
                'name' => $teamNames[$teamIndex],
                'description' => "Equipo de prueba número " . ($teamIndex + 1) . " para la competencia test",
                'captain_id' => $users[$userIndex]->id,
                'status' => 'active',
                'current_members' => 15, // Llenar equipos completos
                'logo' => null, // Por ahora sin logo, usará el default
            ]);

            // Agregar 15 miembros al equipo (incluyendo el capitán)
            for ($memberIndex = 0; $memberIndex < 15; $memberIndex++) {
                CompetitionTeamMember::create([
                    'team_id' => $team->id,
                    'user_id' => $users[$userIndex]->id,
                    'is_captain' => $memberIndex === 0, // El primer miembro es el capitán
                    'status' => 'active',
                    'joined_at' => Carbon::now()->subDays(rand(1, 10)),
                ]);
                
                $userIndex++;
            }
        }

        $this->command->info('✅ Seeder completado exitosamente:');
        $this->command->info("- Competencia 'Competicion Test 3' creada");
        $this->command->info("- 300 usuarios de prueba creados (test1@example.com a test300@example.com)");
        $this->command->info("- 20 equipos creados con 15 miembros cada uno");
        $this->command->info("- Todas las suscripciones están activas");
        $this->command->info("- Contraseña para todos los usuarios: 'password'");
    }
}
