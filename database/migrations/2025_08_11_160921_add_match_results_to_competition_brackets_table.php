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
            // Solo agregar las columnas que no existen
            if (!Schema::hasColumn('competition_brackets', 'evidence_file')) {
                $table->string('evidence_file')->nullable()->after('notes')->comment('Archivo de evidencia del resultado');
            }
            if (!Schema::hasColumn('competition_brackets', 'evidence_type')) {
                $table->string('evidence_type')->nullable()->after('evidence_file')->comment('Tipo de evidencia: photo, pdf, etc');
            }
            if (!Schema::hasColumn('competition_brackets', 'result_registered_by')) {
                $table->unsignedBigInteger('result_registered_by')->nullable()->after('evidence_type');
            }
            if (!Schema::hasColumn('competition_brackets', 'result_registered_at')) {
                $table->timestamp('result_registered_at')->nullable()->after('result_registered_by');
            }
            
            // Agregar foreign key si no existe
            if (!Schema::hasColumn('competition_brackets', 'result_registered_by') || 
                !collect(Schema::getForeignKeys('competition_brackets'))->pluck('foreign_key')->contains('result_registered_by')) {
                $table->foreign('result_registered_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competition_brackets', function (Blueprint $table) {
            // Solo eliminar las columnas que agregamos
            if (Schema::hasColumn('competition_brackets', 'result_registered_by')) {
                $table->dropForeign(['result_registered_by']);
                $table->dropColumn('result_registered_by');
            }
            if (Schema::hasColumn('competition_brackets', 'result_registered_at')) {
                $table->dropColumn('result_registered_at');
            }
            if (Schema::hasColumn('competition_brackets', 'evidence_type')) {
                $table->dropColumn('evidence_type');
            }
            if (Schema::hasColumn('competition_brackets', 'evidence_file')) {
                $table->dropColumn('evidence_file');
            }
        });
    }
};
