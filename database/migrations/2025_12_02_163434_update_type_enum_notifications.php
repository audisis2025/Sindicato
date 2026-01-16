<?php
/*
* Nombre de la clase         : 2025_12_02_163434_update_type_enum_on_system_notifications_table.php
* Descripción de la clase    : Migración para actualizar el ENUM del campo type en la tabla de notificaciones del sistema,
*                              ampliando los tipos disponibles para reflejar distintos estados y eventos del flujo
*                              de trámites y notificaciones internas.
* Fecha de creación          : 02/12/2025
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
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE system_notifications
            MODIFY COLUMN type ENUM(
                'info',
                'success',
                'error',
                'warning',
                'pending',
                'approved',
                'rejected',
                'correction'
            ) NOT NULL DEFAULT 'info'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE system_notifications
            MODIFY COLUMN type ENUM(
                'info',
                'error',
                'correction'
            ) NOT NULL DEFAULT 'info'
        ");
    }
};
