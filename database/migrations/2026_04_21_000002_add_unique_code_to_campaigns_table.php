<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'unique_code')) {
                $table->string('unique_code', 100)->nullable()->unique()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'unique_code')) {
                $table->dropUnique(['unique_code']);
                $table->dropColumn('unique_code');
            }
        });
    }
};
