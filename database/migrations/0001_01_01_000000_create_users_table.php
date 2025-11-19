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
        // Tabla de usuarios (Todo en inglés)
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Campo de login principal
            $table->string('username', 50)->unique(); // Traducido de 'usuario'
            
            $table->string('name');
            
            // Email opcional
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // --- CAMPOS CONSOLIDADOS (Traducidos) ---
            
            // Roles del sistema (en inglés)
            $table->enum('role', ['admin', 'union', 'worker']) // Traducido de 'rol' y valores
                  ->default('worker');
            
            // Estado del usuario (en inglés)
            $table->boolean('active')->default(true); // Traducido de 'activo'

            // Datos del trabajador (en inglés)
            $table->string('curp', 18)->nullable();
            $table->string('rfc', 13)->nullable();
            $table->enum('gender', ['H', 'M'])->nullable(); // Traducido de 'sexo'
            $table->string('budget_key')->nullable(); // Traducido de 'clave_presupuestal'
            // -----------------------------

            $table->rememberToken();
            $table->timestamps();
        });

        // Las tablas de password_reset_tokens y sessions ya están en inglés
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};