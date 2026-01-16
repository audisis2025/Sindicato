<?php
/*
* Nombre de la clase         : 2025_11_24_152023_update_users_email_and_remove_username_table.php
* Descripción de la clase    : Migración para actualizar la estructura de la tabla users, estableciendo el campo email como obligatorio y único, y eliminando la columna username.
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
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->unique()->change();

            $table->dropColumn('username');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->dropUnique(['email']);

            $table->string('username', 50)->unique();
        });
    }
};
