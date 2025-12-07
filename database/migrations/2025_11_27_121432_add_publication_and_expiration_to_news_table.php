<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {

            if (!Schema::hasColumn('news', 'publication_date')) {
                $table->date('publication_date')->nullable()->after('content');
            }

            if (!Schema::hasColumn('news', 'expiration_date')) {
                $table->date('expiration_date')->nullable()->after('publication_date');
            }

            if (!Schema::hasColumn('news', 'image_path')) {
                $table->string('image_path')->nullable()->after('file_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'publication_date')) {
                $table->dropColumn('publication_date');
            }
            if (Schema::hasColumn('news', 'expiration_date')) {
                $table->dropColumn('expiration_date');
            }
            if (Schema::hasColumn('news', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};
