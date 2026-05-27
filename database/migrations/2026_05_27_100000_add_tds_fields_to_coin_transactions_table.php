<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('coin_transactions')) {
            return;
        }

        Schema::table('coin_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('coin_transactions', 'net_amount')) {
                $table->decimal('net_amount', 10, 2)->nullable()->after('tds');
            }
            if (!Schema::hasColumn('coin_transactions', 'tds_rate')) {
                $table->decimal('tds_rate', 5, 2)->nullable()->after('net_amount');
            }
            if (!Schema::hasColumn('coin_transactions', 'tds_section')) {
                $table->string('tds_section', 10)->default('194C')->after('tds_rate');
            }
            if (!Schema::hasColumn('coin_transactions', 'pan_status_at_withdrawal')) {
                $table->string('pan_status_at_withdrawal', 30)->nullable()->after('tds_section');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('coin_transactions')) {
            return;
        }

        Schema::table('coin_transactions', function (Blueprint $table) {
            $columns = ['net_amount', 'tds_rate', 'tds_section', 'pan_status_at_withdrawal'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('coin_transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
