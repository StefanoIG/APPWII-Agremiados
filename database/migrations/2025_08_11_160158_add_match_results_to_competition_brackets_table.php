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
            // Campos para registrar resultados del partido
            $table->string('team1_score')->nullable()->after('team2_id'); // Marcador equipo 1
            $table->string('team2_score')->nullable()->after('team1_score'); // Marcador equipo 2
            $table->unsignedBigInteger('winner_team_id')->nullable()->after('team2_score'); // ID del equipo ganador
            $table->enum('match_status', ['pending', 'completed', 'cancelled'])->default('pending')->after('winner_team_id');
            $table->text('match_notes')->nullable()->after('match_status'); // Notas adicionales del partido
            $table->string('evidence_file')->nullable()->after('match_notes'); // Archivo de evidencia (foto/pdf)
            $table->timestamp('match_date')->nullable()->after('evidence_file'); // Fecha del partido
            $table->unsignedBigInteger('recorded_by')->nullable()->after('match_date'); // Quien registró el resultado
            
            // Relaciones
            $table->foreign('winner_team_id')->references('id')->on('competition_teams')->onDelete('set null');
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
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
