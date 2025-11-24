<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procedure_steps', function (Blueprint $table) {
            if (!Schema::hasColumn('procedure_steps', 'requires_file')) {
                $table->boolean('requires_file')->default(false)->after('step_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('procedure_steps', function (Blueprint $table) {
            if (Schema::hasColumn('procedure_steps', 'requires_file')) {
                $table->dropColumn('requires_file');
            }
        });
    }
};
