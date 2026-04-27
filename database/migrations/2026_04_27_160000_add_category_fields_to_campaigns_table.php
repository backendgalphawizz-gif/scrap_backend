<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('brand_id');
            }

            if (!Schema::hasColumn('campaigns', 'sub_category_id')) {
                $table->unsignedBigInteger('sub_category_id')->nullable()->after('category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'sub_category_id')) {
                $table->dropColumn('sub_category_id');
            }

            if (Schema::hasColumn('campaigns', 'category_id')) {
                $table->dropColumn('category_id');
            }
        });
    }
};
