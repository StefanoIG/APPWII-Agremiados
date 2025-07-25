<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verificar y agregar campos solo si no existen
            if (!Schema::hasColumn('users', 'identification_number')) {
                $table->string('identification_number')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('birth_date');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['M', 'F', 'Otro'])->nullable()->after('address');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('users', 'profession')) {
                $table->string('profession')->nullable()->after('emergency_contact_phone');
            }
        });

        // Actualizar registros existentes con valores por defecto para identification_number
        if (Schema::hasColumn('users', 'identification_number')) {
            DB::table('users')
                ->whereNull('identification_number')
                ->orWhere('identification_number', '')
                ->update([
                    'identification_number' => DB::raw('CONCAT("TEMP_", id)')
                ]);

            // Verificar si ya existe el índice unique antes de crearlo
            $indexExists = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_identification_number_unique'");
            if (empty($indexExists)) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unique('identification_number');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar índice unique si existe
            $indexExists = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_identification_number_unique'");
            if (!empty($indexExists)) {
                $table->dropUnique('users_identification_number_unique');
            }
            
            // Eliminar columnas solo si existen
            $columnsToRemove = [
                'identification_number',
                'phone',
                'birth_date',
                'address',
                'gender',
                'emergency_contact_name',
                'emergency_contact_phone',
                'profession'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
