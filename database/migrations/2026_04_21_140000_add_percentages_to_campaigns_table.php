<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->unsignedTinyInteger('admin_percentage')->default(30)->after('unique_code');
            $table->unsignedTinyInteger('user_percentage')->default(50)->after('admin_percentage');
            $table->unsignedTinyInteger('sales_percentage')->default(20)->after('user_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['admin_percentage', 'user_percentage', 'sales_percentage']);
        });
    }
};
