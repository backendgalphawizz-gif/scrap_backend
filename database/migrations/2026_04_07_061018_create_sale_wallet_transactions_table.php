<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sale_wallet_transactions')) {
            return;
        }

        Schema::create('sale_wallet_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_id');
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('type', ['credit', 'debit'])->default('credit');
            $table->mediumText('remarks')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_wallet_transactions');
    }
};
