<?php
/*
* Nombre de la clase         : 2025_11_21_162319_add_image_path_to_news_table.php
* Descripción de la clase    : Migración para agregar la columna image_path a la tabla de noticias, permitiendo asociar imágenes a comunicados, convocatorias y eventos.
* Fecha de creación          : 21/11/2025
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
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'image_path')) {
                $table->string('image_path')->nullable()->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};
