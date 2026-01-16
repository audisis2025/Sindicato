<?php
/*
* Nombre de la clase         : 2025_11_10_224011_create_procedure_documents_table.php
* Descripción de la clase    : Migración para la creación de la tabla de documentos asociados a las solicitudes de trámites, incluyendo información del archivo, tipo y año.
* Fecha de creación            : 10/11/2025
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
    public function up(): void
    {
        Schema::create('procedure_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_request_id')->constrained('procedure_requests')->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('type')->default('pdf');
            $table->integer('year')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedure_documents');
    }
};