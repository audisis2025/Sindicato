<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedure_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_id')->constrained('procedures')->onDelete('cascade');
            $table->integer('order');
            $table->string('step_name');
            $table->text('step_description')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('next_step_if_fail')->nullable();
            $table->timestamps();
            $table->unique(['procedure_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedure_steps');
    }
};