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
        Schema::create('competition_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('competition_teams');
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('is_captain')->default(false);
            $table->enum('status', ['active', 'inactive', 'removed'])->default('active');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['team_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_team_members');
    }
};
