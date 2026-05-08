<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('campaign_id');
            $table->unsignedBigInteger('brand_id');
            $table->decimal('calculated_amount', 10, 2)->default(0)
                  ->comment('System-calculated refundable amount');
            $table->decimal('refunded_amount', 10, 2)->nullable()
                  ->comment('Actual amount admin confirmed to refund');
            // Bank detail snapshot at time of refund initiation
            $table->string('bank_account_number', 30)->nullable();
            $table->string('bank_ifsc_code', 20)->nullable();
            $table->string('bank_account_holder_name', 100)->nullable();
            $table->enum('bank_account_type', ['savings', 'current'])->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('sellers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_refunds');
    }
};
