<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Fixing duplicate migrations...\n";

// Migraciones duplicadas que ya existen en la base de datos
$duplicateMigrations = [
    '2025_07_25_034536_create_categorias_table',
    '2025_07_25_034544_create_disciplinas_table'
];

$currentBatch = DB::table('migrations')->max('batch') ?? 0;
$nextBatch = $currentBatch + 1;

foreach ($duplicateMigrations as $migration) {
    // Verificar si la migración ya está marcada como ejecutada
    $exists = DB::table('migrations')->where('migration', $migration)->exists();
    
    if (!$exists) {
        // Marcar como ejecutada
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => $nextBatch
        ]);
        echo "✓ Marked {$migration} as migrated\n";
    } else {
        echo "- {$migration} already marked as migrated\n";
    }
}

echo "\nDone! Now you can run 'php artisan migrate' to execute the new migrations.\n";
