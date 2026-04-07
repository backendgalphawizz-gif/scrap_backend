<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_wallets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id')->nullable();
            $table->decimal('wallet_amount', 10, 2)->nullable()->default(0);
            $table->decimal('hold_for_campaign', 10, 2)->default(0);
            $table->decimal('lifetime_earning', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_wallets');
    }
};
