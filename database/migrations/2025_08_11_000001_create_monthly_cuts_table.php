<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('monthly_cuts', function (Blueprint $table) {
            $table->id();
            $table->string('cut_name'); // Ej: "Enero 2025", "Febrero 2025"
            $table->date('cut_date'); // Fecha de corte
            $table->decimal('amount', 10, 2); // Valor del corte
            $table->text('description')->nullable(); // Descripción del corte
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->unsignedBigInteger('created_by'); // ID de la secretaria que lo creó
            $table->timestamps();
            
            $table->index('cut_date');
            $table->index('status');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_cuts');
    }
};
