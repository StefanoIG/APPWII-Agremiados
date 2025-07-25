<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Plan Mensual
        SubscriptionPlan::create([
            'name' => 'Membresía Mensual',
            'type' => 'monthly',
            'price' => 25.00,
            'duration_months' => 1,
            'description' => 'Acceso completo a todas las funcionalidades por un mes. Incluye participación en todas las disciplinas, equipos y eventos especiales.',
            'is_active' => true
        ]);

        // Plan Anual
        SubscriptionPlan::create([
            'name' => 'Membresía Anual',
            'type' => 'yearly',
            'price' => 250.00,
            'duration_months' => 12,
            'description' => 'Acceso completo a todas las funcionalidades por un año completo. ¡Ahorra 2 meses! Incluye prioridad en inscripciones y descuentos especiales.',
            'is_active' => true
        ]);

        // Plan Semestral (opcional)
        SubscriptionPlan::create([
            'name' => 'Membresía Semestral',
            'type' => 'semestral',
            'price' => 140.00,
            'duration_months' => 6,
            'description' => 'Acceso completo por 6 meses, ideal para temporadas específicas. Flexibilidad perfecta para miembros estacionales.',
            'is_active' => true
        ]);
    }
}
