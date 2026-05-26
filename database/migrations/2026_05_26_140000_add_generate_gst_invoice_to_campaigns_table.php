<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('campaigns')) {
            return;
        }

        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'generate_gst_invoice')) {
                $table->boolean('generate_gst_invoice')->default(false)->after('compign_budget_with_gst');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('campaigns')) {
            return;
        }

        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'generate_gst_invoice')) {
                $table->dropColumn('generate_gst_invoice');
            }
        });
    }
};
