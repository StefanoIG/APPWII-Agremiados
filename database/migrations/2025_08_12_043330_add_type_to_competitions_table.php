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
        Schema::table('competitions', function (Blueprint $table) {
            if (!Schema::hasColumn('competitions', 'type')) {
                $table->enum('type', ['competition', 'course'])->default('competition')->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            if (Schema::hasColumn('competitions', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
