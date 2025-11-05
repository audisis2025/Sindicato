<?php

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
        Schema::create('reportes_estadisticos', function (Blueprint $table) {
            $table->id();
            $table->integer('total_tramites')->default(0);
            $table->integer('pendientes')->default(0);
            $table->integer('resueltos')->default(0);
            $table->integer('hombres')->default(0);
            $table->integer('mujeres')->default(0);
            $table->string('zona_mas_fallas')->nullable();
            $table->timestamp('fecha_actualizacion')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes_estadisticos');
    }
};
