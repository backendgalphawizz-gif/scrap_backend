<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->unsignedInteger('sale_id')->nullable()->after('voucher_brands_id');
            $table->date('valid_from')->nullable()->after('validity_days');
            $table->date('valid_to')->nullable()->after('valid_from');
            $table->unsignedInteger('max_uses')->nullable()->after('valid_to');
            $table->unsignedInteger('max_uses_per_user')->default(1)->after('max_uses');
            $table->unsignedInteger('used_count')->default(0)->after('max_uses_per_user');

            $table->index('sale_id');
            $table->index('valid_from');
            $table->index('valid_to');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropIndex(['sale_id']);
            $table->dropIndex(['valid_from']);
            $table->dropIndex(['valid_to']);

            $table->dropColumn([
                'sale_id',
                'valid_from',
                'valid_to',
                'max_uses',
                'max_uses_per_user',
                'used_count',
            ]);
        });
    }
};
