<?php
/*
* Nombre de la clase         : 2025_12_03_153610_remove_estimated_days_from_procedure_steps_table.php
* Descripción de la clase    : Migración para eliminar la columna estimated_days de la tabla de pasos de los trámites,
*                              ajustando la estructura para simplificar la gestión de duración por paso.
* Fecha de creación          : 22/11/2025
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
    public function up(): void
    {
        Schema::table('procedure_steps', function (Blueprint $table) {
            if (Schema::hasColumn('procedure_steps', 'estimated_days')) {
                $table->dropColumn('estimated_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('procedure_steps', function (Blueprint $table) {
            $table->unsignedInteger('estimated_days')->nullable();
        });
    }
};
