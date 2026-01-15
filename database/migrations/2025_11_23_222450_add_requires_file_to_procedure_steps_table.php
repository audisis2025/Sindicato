<?php
/*
* Nombre de la clase         : 2025_11_23_222450_add_requires_file_to_procedure_steps_table.php
* Descripción de la clase    : Migración para agregar la columna requires_file a la tabla de pasos de los trámites, permitiendo indicar si un paso requiere la carga obligatoria de un archivo.
* Fecha de creación          : 23/11/2025
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
            if (!Schema::hasColumn('procedure_steps', 'requires_file')) {
                $table->boolean('requires_file')->default(false)->after('step_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('procedure_steps', function (Blueprint $table) {
            if (Schema::hasColumn('procedure_steps', 'requires_file')) {
                $table->dropColumn('requires_file');
            }
        });
    }
};
