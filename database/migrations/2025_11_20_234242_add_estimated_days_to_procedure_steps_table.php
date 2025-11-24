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
        Schema::table('procedure_steps', function (Blueprint $table) {
            $table->integer('estimated_days')->nullable()->after('step_description');
        });
    }

    public function down(): void
    {
        Schema::table('procedure_steps', function (Blueprint $table) {
            $table->dropColumn('estimated_days');
        });
    }
};
