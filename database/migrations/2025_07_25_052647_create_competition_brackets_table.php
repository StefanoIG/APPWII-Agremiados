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
        Schema::create('competition_brackets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->onDelete('cascade');
            $table->foreignId('team1_id')->nullable()->constrained('competition_teams')->onDelete('set null');
            $table->foreignId('team2_id')->nullable()->constrained('competition_teams')->onDelete('set null');
            $table->foreignId('winner_id')->nullable()->constrained('competition_teams')->onDelete('set null');
            $table->integer('round'); // 1 = primera ronda, 2 = segunda ronda, etc.
            $table->integer('position'); // posición en la ronda
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->datetime('match_date')->nullable();
            $table->integer('team1_score')->nullable();
            $table->integer('team2_score')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['competition_id', 'round', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_brackets');
    }
};
