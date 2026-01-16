<?php
/*
* Nombre de la clase         : 2025_11_20_234242_add_estimated_days_to_procedure_steps_table.php
* Descripción de la clase    : Migración para agregar el campo de días estimados a los pasos de los trámites, permitiendo definir la duración aproximada de cada paso.
* Fecha de creación          : 20/11/2025
* Elaboró                    : Iker Piza
* Fecha de liberación        : 19/12/2025
* Autorizó                   : Salvador Monroy
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
        Schema::table('procedure_steps', function (Blueprint $table) {
            $table->integer('estimated_days')->nullable()->after('step_description');
        });
    }

    public function down(): void
    {
        Schema::table('procedure_steps', function (Blueprint $table) {
            $table->dropColumn('estimated_days');
        });
    }
};
