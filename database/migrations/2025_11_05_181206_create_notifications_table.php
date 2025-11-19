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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            // --- CAMPOS TRADUCIDOS ---
            $table->string('title', 255); // Antes 'titulo'
            $table->text('message'); // Antes 'mensaje'

            // Enum y valores traducidos
            $table->enum('type', ['info', 'error', 'correction'])
                  ->default('info'); // Antes 'tipo' y 'aviso'

            // Enum y valores traducidos
            $table->enum('status', ['unread', 'read'])
                  ->default('unread'); // Antes 'estado', 'no_leida', 'leida'
            // --- FIN ---

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};