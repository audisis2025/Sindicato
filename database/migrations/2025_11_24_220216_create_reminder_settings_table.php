<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminder_settings', function (Blueprint $table) {
            $table->id();

            $table->boolean('enabled')->default(false);
            $table->enum('channel', ['email', 'inapp'])->default('email');
            $table->integer('interval_days')->default(2);
            $table->text('base_message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_settings');
    }
};
