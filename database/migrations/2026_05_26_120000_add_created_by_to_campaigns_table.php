<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('campaigns') || Schema::hasColumn('campaigns', 'created_by')) {
            return;
        }

        Schema::table('campaigns', function (Blueprint $table) {
            $table->enum('created_by', ['brand', 'sales_person'])
                ->nullable()
                ->after('sale_id')
                ->comment('Who created the campaign: brand app or sales person app');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('campaigns') || !Schema::hasColumn('campaigns', 'created_by')) {
            return;
        }

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
};
