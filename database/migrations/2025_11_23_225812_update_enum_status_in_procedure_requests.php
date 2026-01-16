<?php
/*
* Nombre de la clase         : 2025_11_23_225812_update_status_enum_on_procedure_requests_table.php
* Descripción de la clase    : Migración para actualizar el ENUM del campo status en la tabla procedure_requests, ampliando los estados del flujo del trámite e incorporando valores iniciados, pendientes y cancelados.
* Fecha de creación          : 22/11/2025
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
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE procedure_requests 
            MODIFY status ENUM(
                'initiated',
                'in_progress',
                'pending_worker',
                'pending_union',
                'completed',
                'cancelled',
                'rejected'
            ) NOT NULL DEFAULT 'initiated'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE procedure_requests 
            MODIFY status ENUM(
                'pending',
                'in_progress',
                'completed',
                'rejected'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};
