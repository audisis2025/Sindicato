<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procedure_documents', function (Blueprint $table) {

            // Nueva columna para enlazar archivo ↔ paso
            $table->foreignId('procedure_step_id')
                  ->nullable()
                  ->after('procedure_request_id')
                  ->constrained('procedure_steps')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('procedure_documents', function (Blueprint $table) {
            // Eliminar la FK y columna
            $table->dropForeign(['procedure_step_id']);
            $table->dropColumn('procedure_step_id');
        });
    }
};
