<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vouchers')) {
            return;
        }

        Schema::table('vouchers', function (Blueprint $table) {
            if (! Schema::hasColumn('vouchers', 'sale_id')) {
                $table->unsignedInteger('sale_id')->nullable()->after('voucher_brands_id');
            }
            if (! Schema::hasColumn('vouchers', 'valid_from')) {
                $table->date('valid_from')->nullable()->after('validity_days');
            }
            if (! Schema::hasColumn('vouchers', 'valid_to')) {
                $table->date('valid_to')->nullable()->after('valid_from');
            }
            if (! Schema::hasColumn('vouchers', 'max_uses')) {
                $table->unsignedInteger('max_uses')->nullable()->after('valid_to');
            }
            if (! Schema::hasColumn('vouchers', 'max_uses_per_user')) {
                $table->unsignedInteger('max_uses_per_user')->default(1)->after('max_uses');
            }
            if (! Schema::hasColumn('vouchers', 'used_count')) {
                $table->unsignedInteger('used_count')->default(0)->after('max_uses_per_user');
            }
        });

        $indexNames = collect(DB::select('SHOW INDEX FROM vouchers'))
            ->pluck('Key_name')
            ->unique();

        Schema::table('vouchers', function (Blueprint $table) use ($indexNames) {
            if (Schema::hasColumn('vouchers', 'sale_id') && ! $indexNames->contains('vouchers_sale_id_index')) {
                $table->index('sale_id');
            }
            if (Schema::hasColumn('vouchers', 'valid_from') && ! $indexNames->contains('vouchers_valid_from_index')) {
                $table->index('valid_from');
            }
            if (Schema::hasColumn('vouchers', 'valid_to') && ! $indexNames->contains('vouchers_valid_to_index')) {
                $table->index('valid_to');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('vouchers')) {
            return;
        }

        $indexNames = collect(DB::select('SHOW INDEX FROM vouchers'))
            ->pluck('Key_name')
            ->unique();

        Schema::table('vouchers', function (Blueprint $table) use ($indexNames) {
            if ($indexNames->contains('vouchers_sale_id_index')) {
                $table->dropIndex(['sale_id']);
            }
            if ($indexNames->contains('vouchers_valid_from_index')) {
                $table->dropIndex(['valid_from']);
            }
            if ($indexNames->contains('vouchers_valid_to_index')) {
                $table->dropIndex(['valid_to']);
            }

            $columns = array_filter([
                Schema::hasColumn('vouchers', 'sale_id') ? 'sale_id' : null,
                Schema::hasColumn('vouchers', 'valid_from') ? 'valid_from' : null,
                Schema::hasColumn('vouchers', 'valid_to') ? 'valid_to' : null,
                Schema::hasColumn('vouchers', 'max_uses') ? 'max_uses' : null,
                Schema::hasColumn('vouchers', 'max_uses_per_user') ? 'max_uses_per_user' : null,
                Schema::hasColumn('vouchers', 'used_count') ? 'used_count' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
