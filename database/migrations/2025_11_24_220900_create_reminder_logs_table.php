<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminder_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');        // trabajador
            $table->unsignedBigInteger('procedure_id');   // trámite
            $table->string('channel');                    // email | inapp
            $table->text('message');                      // mensaje enviado
            $table->date('sent_at');                      // fecha del envío

            $table->timestamps();

            // Relaciones
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('procedure_id')->references('id')->on('procedure_requests')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_logs');
    }
};
