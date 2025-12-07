<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('notifications', 'system_notifications');
    }

    public function down(): void
    {
        Schema::rename('system_notifications', 'notifications');
    }
};
