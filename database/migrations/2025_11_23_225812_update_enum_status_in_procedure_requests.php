<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE procedure_requests 
            MODIFY status ENUM(
                'initiated',
                'in_progress',
                'pending_worker',
                'pending_union',
                'completed',
                'cancelled',
                'rejected'
            ) NOT NULL DEFAULT 'initiated'
        ");
    }

    public function down(): void
    {
        // Revertir al enum anterior (por si hicieras rollback)
        DB::statement("
            ALTER TABLE procedure_requests 
            MODIFY status ENUM(
                'pending',
                'in_progress',
                'completed',
                'rejected'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};
