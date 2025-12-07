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

            $table->string('name', 255);
            
            $table->text('description')->nullable();


            $table->enum('status', ['active', 'inactive', 'archived'])
                  ->default('active');
            

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