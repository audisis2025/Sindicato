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
        Schema::create('usuarios_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('curp', 18)->nullable();
            $table->string('rfc', 13)->nullable();
            $table->enum('sexo', ['H', 'M'])->nullable();
            $table->string('clave_presupuestal')->nullable();
            $table->enum('rol', ['administrador', 'sindicato', 'trabajador'])->default('trabajador');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_detalle');
    }
};
