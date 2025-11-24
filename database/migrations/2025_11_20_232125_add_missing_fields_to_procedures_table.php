<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procedures', function (Blueprint $table) {

            // Dueño (Sindicato que crea el trámite)
            $table->foreignId('user_id')
                ->after('id')
                ->constrained('users')
                ->onDelete('cascade');

            // Campos reales usados en el modelo
            $table->integer('steps_count')->after('description')->default(1);
            $table->date('opening_date')->nullable();
            $table->date('closing_date')->nullable();
            $table->integer('estimated_days')->nullable();
            $table->boolean('has_alternate_flow')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'steps_count',
                'opening_date',
                'closing_date',
                'estimated_days',
                'has_alternate_flow',
            ]);
        });
    }
};
