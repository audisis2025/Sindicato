<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'usuario')) {
                $table->string('usuario', 50)->unique()->after('id');
            }

            if (!Schema::hasColumn('users', 'curp')) {
                $table->string('curp', 18)->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'rfc')) {
                $table->string('rfc', 13)->nullable()->after('curp');
            }

            if (!Schema::hasColumn('users', 'sexo')) {
                $table->enum('sexo', ['H', 'M'])->nullable()->after('rfc');
            }

            if (!Schema::hasColumn('users', 'clave_presupuestal')) {
                $table->string('clave_presupuestal')->nullable()->after('sexo');
            }

            if (!Schema::hasColumn('users', 'rol')) {
                $table->enum('rol', ['administrador', 'sindicato', 'trabajador'])->default('trabajador')->after('password');
            }

            if (!Schema::hasColumn('users', 'activo')) {
                $table->boolean('activo')->default(true)->after('rol');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['usuario', 'curp', 'rfc', 'sexo', 'clave_presupuestal', 'rol', 'activo'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
