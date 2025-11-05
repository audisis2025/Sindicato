<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ✅ Nuevo campo 'usuario' (nombre de usuario único)
            $table->string('usuario', 50)->unique()->after('id');

            // ✅ Permitir que el email sea opcional (nullable)
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revertir los cambios
            $table->dropColumn('usuario');
            $table->string('email')->nullable(false)->change();
        });
    }
};
