<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('seller_wallet_histories')) {
            return;
        }

        Schema::create('seller_wallet_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seller_id')->nullable();
            $table->double('amount')->default(0);
            $table->enum('type', ['credit', 'debit'])->default('credit');
            $table->string('remarks', 45)->nullable();
            $table->decimal('available_balance', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_wallet_histories');
    }
};
