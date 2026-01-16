<?php
/*
* Nombre de la clase         : 2025_11_10_224006_create_procedure_steps_table.php
* Descripción de la clase    : Migración para la creación de la tabla de pasos de los trámites, definiendo el orden, descripción, archivos asociados y flujo alterno en caso de error.
* Fecha de creación            : 10/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 14/12/2025
* Autorizó                     : Salvador Monroy
* Versión                      : 1.0
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        : 
* Descripción del mantenimiento: 
* Responsable                  :
* Revisor                      :
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedure_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_id')->constrained('procedures')->onDelete('cascade');
            $table->integer('order');
            $table->string('step_name');
            $table->text('step_description')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('next_step_if_fail')->nullable();
            $table->timestamps();
            $table->unique(['procedure_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedure_steps');
    }
};