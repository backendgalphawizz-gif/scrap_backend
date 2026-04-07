<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coin_wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('withdrawlable_balance', 10, 2)->default(0);
            $table->boolean('status')->default(true)->comment('0 = Inactive, 1 = Active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_wallets');
    }
};
