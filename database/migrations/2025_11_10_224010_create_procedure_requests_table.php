<?php

/*
* ===========================================================
* Nombre de la clase: create_procedure_requests_table
* Descripción: Migración para la tabla 'procedure_requests' (solicitudes de trámite).
* Almacena las instancias de un trámite iniciadas por un usuario.
* Fecha de creación: 10/11/2025
* Elaboró: [Autor Original]
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 2.0
*
* Fecha de mantenimiento: 12/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo (Alineación)
* Descripción del mantenimiento: Se traducen los valores del enum 'status' a inglés.
* Responsable: [Tu Nombre]
* Revisor: Gemini
* ===========================================================
*/

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
        Schema::create('procedure_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_id')->constrained('procedures')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('current_step')->default(1);

            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])
                  ->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_requests');
    }
};