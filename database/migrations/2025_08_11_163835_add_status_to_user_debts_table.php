<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_debts', function (Blueprint $table) {
            // Verificar si la columna ya existe antes de agregarla
            if (!Schema::hasColumn('user_debts', 'status')) {
                $table->enum('status', ['pending', 'pending_approval', 'paid', 'overdue'])->default('pending')->after('amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_debts', function (Blueprint $table) {
            if (Schema::hasColumn('user_debts', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
