<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_wallet_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_wallet_transactions', 'tds')) {
                $table->decimal('tds', 10, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('sale_wallet_transactions', 'net_amount')) {
                $table->decimal('net_amount', 10, 2)->nullable()->after('tds');
            }
            if (!Schema::hasColumn('sale_wallet_transactions', 'tds_rate')) {
                $table->decimal('tds_rate', 5, 2)->nullable()->after('net_amount');
            }
            if (!Schema::hasColumn('sale_wallet_transactions', 'tds_section')) {
                $table->string('tds_section', 10)->default('194H')->after('tds_rate');
            }
            if (!Schema::hasColumn('sale_wallet_transactions', 'pan_status_at_withdrawal')) {
                $table->string('pan_status_at_withdrawal', 30)->nullable()->after('tds_section');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sale_wallet_transactions', function (Blueprint $table) {
            $columns = ['tds', 'net_amount', 'tds_rate', 'tds_section', 'pan_status_at_withdrawal'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('sale_wallet_transactions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
