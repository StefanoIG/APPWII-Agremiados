<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\Disciplina;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías de ejemplo
        $categorias = [
            ['name' => 'Juvenil', 'description' => 'Categoría para jóvenes de 15-20 años'],
            ['name' => 'Adultos', 'description' => 'Categoría para adultos de 21-35 años'],
            ['name' => 'Senior', 'description' => 'Categoría para adultos de 36+ años'],
            ['name' => 'Mixto', 'description' => 'Categoría mixta sin restricción de edad'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['name' => $categoria['name']],
                $categoria
            );
        }

        // Crear disciplinas de ejemplo
        $disciplinas = [
            ['name' => 'Fútbol', 'description' => 'Fútbol 11 tradicional'],
            ['name' => 'Fútbol Sala', 'description' => 'Fútbol sala 5vs5'],
            ['name' => 'Baloncesto', 'description' => 'Baloncesto tradicional'],
            ['name' => 'Voleibol', 'description' => 'Voleibol 6vs6'],
            ['name' => 'Tenis de Mesa', 'description' => 'Ping pong individual o dobles'],
            ['name' => 'Ajedrez', 'description' => 'Ajedrez individual'],
        ];

        foreach ($disciplinas as $disciplina) {
            Disciplina::firstOrCreate(
                ['name' => $disciplina['name']],
                $disciplina
            );
        }

        $this->command->info('Categorías y disciplinas creadas exitosamente.');
    }
}
