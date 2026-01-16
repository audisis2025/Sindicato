<?php
/*
* Nombre de la clase         : 2025_11_05_181206_create_notifications_table.php
* Descripción de la clase    : Migración para la creación de la tabla de notificaciones del sistema, asociadas a usuarios, con control de tipo y estatus (leída/no leída).
* Fecha de creación            : 05/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 14/12/2025
* Autorizó                     : Salvador Monroy
* Versión                      : 1.0
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        : 
* Descripción del mantenimiento: 
* Responsable                  :
* Revisor                      :
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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('title', 255);
            $table->text('message');
            $table->enum('type', ['info', 'error', 'correction'])
                ->default('info');
            $table->enum('status', ['unread', 'read'])
                ->default('unread');
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
