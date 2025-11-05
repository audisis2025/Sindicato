<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tramite_pasos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites')->onDelete('cascade');
            $table->unsignedInteger('orden'); // 1,2,3...
            $table->string('nombre_paso');
            $table->text('descripcion_paso')->nullable();
            $table->unsignedInteger('tiempo_estimado_dias')->nullable();
            $table->string('formato_path')->nullable(); // archivo opcional del paso
            $table->unsignedInteger('next_step_if_fail')->nullable(); 
            // p.ej. en el paso 2, si falla → 1 (reinscribirse)
            $table->timestamps();

            $table->unique(['tramite_id','orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramite_pasos');
    }
};
