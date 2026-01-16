<?php
/*
* Nombre de la clase         : 2025_01_14_141137_add_read_at_to_system_notifications_table.php
* Descripción de la clase    : Migración para agregar la columna read_at a la tabla de notificaciones del sistema,
*                              permitiendo registrar la fecha y hora en que una notificación fue leída.
* Fecha de creación          : 14/01/2026
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
		Schema::table('system_notifications', function (Blueprint $table)
		{
			$table->timestamp('read_at')->nullable()->after('status');
		});
	}

	public function down(): void
	{
		Schema::table('system_notifications', function (Blueprint $table)
		{
			$table->dropColumn('read_at');
		});
	}
};
