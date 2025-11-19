<?php

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