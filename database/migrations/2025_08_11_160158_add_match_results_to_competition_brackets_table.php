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
        Schema::table('competition_brackets', function (Blueprint $table) {
            // Verificar si las columnas ya existen antes de agregarlas
            if (!Schema::hasColumn('competition_brackets', 'team1_score')) {
                $table->string('team1_score')->nullable()->after('team2_id'); // Marcador equipo 1
            }
            if (!Schema::hasColumn('competition_brackets', 'team2_score')) {
                $table->string('team2_score')->nullable()->after('team1_score'); // Marcador equipo 2
            }
            if (!Schema::hasColumn('competition_brackets', 'winner_team_id')) {
                $table->unsignedBigInteger('winner_team_id')->nullable()->after('team2_score'); // ID del equipo ganador
            }
            if (!Schema::hasColumn('competition_brackets', 'match_status')) {
                $table->enum('match_status', ['pending', 'completed', 'cancelled'])->default('pending')->after('winner_team_id');
            }
            if (!Schema::hasColumn('competition_brackets', 'match_notes')) {
                $table->text('match_notes')->nullable()->after('match_status'); // Notas adicionales del partido
            }
            if (!Schema::hasColumn('competition_brackets', 'evidence_file')) {
                $table->string('evidence_file')->nullable()->after('match_notes'); // Archivo de evidencia (foto/pdf)
            }
            if (!Schema::hasColumn('competition_brackets', 'match_date')) {
                $table->timestamp('match_date')->nullable()->after('evidence_file'); // Fecha del partido
            }
            if (!Schema::hasColumn('competition_brackets', 'recorded_by')) {
                $table->unsignedBigInteger('recorded_by')->nullable()->after('match_date'); // Quien registró el resultado
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competition_brackets', function (Blueprint $table) {
            $table->dropForeign(['winner_team_id']);
            $table->dropForeign(['recorded_by']);
            $table->dropColumn([
                'team1_score',
                'team2_score', 
                'winner_team_id',
                'match_status',
                'match_notes',
                'evidence_file',
                'match_date',
                'recorded_by'
            ]);
        });
    }
};
