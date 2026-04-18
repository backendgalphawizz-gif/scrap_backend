<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('coin_transactions')) {
            return;
        }

        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coin_wallet_id');
            $table->string('transaction_id', 45);
            $table->decimal('coin', 10, 2);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('tds', 10, 2)->default(0);
            $table->decimal('convertion_rate', 10, 3)->default(0);
            $table->enum('type', ['credit', 'debit'])->default('credit');
            $table->string('description', 150)->nullable();
            $table->string('campaign_id', 11);
            $table->enum('status', ['pending', 'rejected', 'completed'])->default('pending');
            $table->string('transaction_type', 45)->nullable();
            $table->string('value', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_transactions');
    }
};
