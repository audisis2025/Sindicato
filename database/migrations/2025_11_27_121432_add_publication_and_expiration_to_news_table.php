<?php
/*
* Nombre de la clase         : 2025_11_27_121432_add_publication_expiration_and_image_to_news_table.php
* Descripción de la clase    : Migración para agregar campos de publicación y expiración a la tabla de noticias, así como
*                              la columna image_path para asociar imágenes a comunicados, convocatorias y eventos.
* Fecha de creación          : 27/11/2025
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

            if (!Schema::hasColumn('news', 'publication_date')) {
                $table->date('publication_date')->nullable()->after('content');
            }

            if (!Schema::hasColumn('news', 'expiration_date')) {
                $table->date('expiration_date')->nullable()->after('publication_date');
            }

            if (!Schema::hasColumn('news', 'image_path')) {
                $table->string('image_path')->nullable()->after('file_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'publication_date')) {
                $table->dropColumn('publication_date');
            }
            if (Schema::hasColumn('news', 'expiration_date')) {
                $table->dropColumn('expiration_date');
            }
            if (Schema::hasColumn('news', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};
