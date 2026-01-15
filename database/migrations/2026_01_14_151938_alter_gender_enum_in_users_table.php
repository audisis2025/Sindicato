<?php
/*
* Nombre de la clase         : 2025_01_14_151938_update_gender_enum_on_users_table.php
* Descripción de la clase    : Migración para actualizar el ENUM del campo gender en la tabla de usuarios,
*                              incorporando opciones adicionales para una representación más inclusiva
*                              y flexible del sexo / género.
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
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
	public function up(): void
	{
		DB::statement("ALTER TABLE users MODIFY gender ENUM('H','M','ND','X') NULL");
	}

	public function down(): void
	{
		DB::statement("ALTER TABLE users MODIFY gender ENUM('H','M') NULL");
	}
};
