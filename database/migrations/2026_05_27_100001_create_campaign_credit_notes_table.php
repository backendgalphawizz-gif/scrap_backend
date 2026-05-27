<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('campaign_credit_notes')) {
            return;
        }

        Schema::create('campaign_credit_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('campaign_refund_id')->nullable();
            $table->unsignedBigInteger('brand_id');
            $table->string('original_invoice_no');
            $table->string('credit_note_no')->unique();
            $table->decimal('taxable_reversal_amount', 12, 2)->default(0);
            $table->decimal('gst_reversal_amount', 12, 2)->default(0);
            $table->decimal('cgst_reversal', 12, 2)->default(0);
            $table->decimal('sgst_reversal', 12, 2)->default(0);
            $table->text('reason')->nullable();
            $table->date('credit_note_date');
            $table->string('status', 20)->default('issued');
            $table->timestamps();

            $table->index('campaign_id');
            $table->index('brand_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_credit_notes');
    }
};
