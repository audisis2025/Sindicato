<?php
/*
* Nombre de la clase         : 2025_11_22_223018_add_procedure_step_id_to_procedure_documents_table.php
* Descripción de la clase    : Migración para agregar la relación con los pasos del trámite a la tabla de documentos, permitiendo asociar cada archivo a un paso específico del proceso.
* Fecha de creación          : 23/11/2025
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
    public function up(): void
    {
        Schema::table('procedure_documents', function (Blueprint $table) {

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
            $table->dropForeign(['procedure_step_id']);
            $table->dropColumn('procedure_step_id');
        });
    }
};
