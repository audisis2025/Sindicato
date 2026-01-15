<?php
/*
* Nombre de la clase         : 2025_11_15_180713_create_news_table.php
* Descripción de la clase    : Migración para la creación de la tabla de noticias del sistema, incluyendo comunicados, convocatorias y eventos, con control de estatus y relación con usuarios.
* Fecha de creación          : 10/11/2025
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
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255); 
            $table->text('content')->nullable(); 
            
            $table->enum('type', ['announcement', 'communication', 'event'])
                  ->default('communication');

            $table->string('file_path')->nullable(); 
            
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('status', ['draft', 'published'])
                  ->default('published');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};