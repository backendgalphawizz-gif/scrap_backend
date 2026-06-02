<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_commission_ledgers', function (Blueprint $table) {
            $table->decimal('discount_absorbed', 10, 2)->default(0)->after('commission_amount')
                ->comment('Amount of voucher discount deducted from this commission entry');
        });
    }

    public function down(): void
    {
        Schema::table('sale_commission_ledgers', function (Blueprint $table) {
            $table->dropColumn('discount_absorbed');
        });
    }
};
