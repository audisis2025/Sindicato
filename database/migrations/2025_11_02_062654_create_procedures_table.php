<?php

/*
* Nombre de la clase     : create_procedures_table
* Descripción de la clase: Migración para crear la tabla 'procedures' (trámites).
* Esta es la tabla maestra que define los trámites disponibles.
* Fecha de creación      : 10/11/2025
* Elaboró                : [Autor Original]
* Fecha de liberación    : 10/11/2025
* Autorizó               : Líder Técnico
* Versión                : 2.0
*
* Fecha de mantenimiento: 12/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo
* Descripción del mantenimiento: Se añaden campos faltantes (name, description, status)
* para que la migración sea funcional.
* Responsable: [Tu Nombre]
* Revisor: Gemini
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
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();

            // --- CAMPOS AÑADIDOS ---
            // Nombre del trámite (ej. "Solicitud de vacaciones")
            $table->string('name', 255);
            
            // Descripción o instrucciones del trámite
            $table->text('description')->nullable();

            // Estado del trámite (si está visible o no para los usuarios)
            $table->enum('status', ['active', 'inactive', 'archived'])
                  ->default('active');
            
            // Se puede añadir un foreignId para el 'dueño' o 'categoría' si se necesita
            // $table->foreignId('category_id')->nullable()->constrained('procedure_categories');

            // --- FIN DE CAMPOS AÑADIDOS ---

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};