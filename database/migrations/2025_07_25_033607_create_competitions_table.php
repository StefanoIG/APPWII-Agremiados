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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('disciplina_id');
            $table->integer('members_per_team');
            $table->integer('max_members');
            $table->integer('min_members');
            $table->integer('max_teams')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('registration_deadline');
            $table->enum('status', ['draft', 'open', 'closed', 'in_progress', 'finished'])->default('draft');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            
            // Añadiremos las claves foráneas más tarde
            $table->index('categoria_id');
            $table->index('disciplina_id');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
