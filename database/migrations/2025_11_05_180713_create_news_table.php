<?php

/*
* ===========================================================
* Nombre de la clase: create_news_table.php
* Descripción: Migración para la tabla 'news' (noticias, convocatorias).
* Fecha de creación: 05/11/2025
* Elaboró: Iker Piza
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 2.1
*
* Fecha de mantenimiento: 10/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo
* Descripción del mantenimiento: Se traducen todas las columnas a inglés...
* Responsable: [Tu Nombre]
* Revisor: [Tu Revisor]
*
* Fecha de mantenimiento: 12/11/2025
* Folio de mantenimiento: [Tu Folio 2]
* Tipo de mantenimiento: Perfectivo (Micro-corrección)
* Descripción del mantenimiento: Se traducen los valores de los enums (type y status) a inglés.
* Responsable: [Tu Nombre]
* Revisor: Gemini
* ===========================================================
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