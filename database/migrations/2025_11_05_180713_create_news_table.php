<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('contenido')->nullable();
            $table->enum('tipo', ['convocatoria', 'comunicado', 'evento'])->default('comunicado');
            $table->string('archivo_path')->nullable();
            $table->unsignedBigInteger('publicado_por')->nullable();
            $table->enum('estado', ['borrador', 'publicada'])->default('publicada');
            $table->timestamps();

            $table->foreign('publicado_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

