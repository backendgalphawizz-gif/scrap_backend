<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coin_wallets', function (Blueprint $table) {
            $table->boolean('withdrawal_frozen')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('coin_wallets', function (Blueprint $table) {
            $table->dropColumn('withdrawal_frozen');
        });
    }
};
