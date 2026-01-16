<?php
/*
* Nombre de la clase         : 2025_11_20_232125_add_missing_fields_to_procedures_table.php
* Descripción de la clase    : Migración para agregar campos faltantes a la tabla de trámites, incluyendo usuario creador, conteo de pasos, fechas de apertura y cierre, duración estimada y control de flujo alterno.
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
    public function up(): void
    {
        Schema::table('procedures', function (Blueprint $table) {

            $table->foreignId('user_id')
                ->after('id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->integer('steps_count')->after('description')->default(1);
            $table->date('opening_date')->nullable();
            $table->date('closing_date')->nullable();
            $table->integer('estimated_days')->nullable();
            $table->boolean('has_alternate_flow')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'steps_count',
                'opening_date',
                'closing_date',
                'estimated_days',
                'has_alternate_flow',
            ]);
        });
    }
};
