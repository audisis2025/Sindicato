<?php
/*
* Nombre de la clase         : 2025_11_02_06254_create_procedures_table.php
* Descripción de la clase    : Migración para la creación de la tabla de trámites del sistema, incluyendo nombre, descripción y estatus del trámite.
* Fecha de creación          : 05/11/2025
* Elaboró                    : Iker Piza
* Fecha de liberación        : 19/12/2025
* Autorizó                   :
* Versión                    : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento :
* Responsable                :
* Revisor                    :
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