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

            $table->unsignedBigInteger('user_id');      
            $table->unsignedBigInteger('procedure_id');   
            $table->string('channel');                   
            $table->text('message');                     
            $table->date('sent_at');                     

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('procedure_id')->references('id')->on('procedure_requests')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_logs');
    }
};
