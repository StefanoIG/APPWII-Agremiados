<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('monthly_cut_id');
            $table->decimal('amount', 10, 2); // Valor de la deuda
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->date('due_date'); // Fecha de vencimiento
            $table->datetime('paid_at')->nullable(); // Fecha de pago
            $table->string('payment_receipt')->nullable(); // Archivo de comprobante
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('monthly_cut_id')->references('id')->on('monthly_cuts')->onDelete('cascade');
            
            $table->index(['user_id', 'status']);
            $table->index('due_date');
            $table->unique(['user_id', 'monthly_cut_id']); // Un usuario no puede tener dos deudas del mismo corte
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_debts');
    }
};
