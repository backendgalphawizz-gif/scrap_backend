<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_commission_slabs', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_earning', 12, 2)->default(0)->comment('Minimum cumulative earnings (inclusive) to enter this slab (₹)');
            $table->decimal('max_earning', 12, 2)->nullable()->comment('Maximum cumulative earnings (exclusive) for this slab; NULL = no upper limit (last slab)');
            $table->decimal('rate', 5, 2)->comment('Commission rate (%) applied when salesperson falls in this slab');
            $table->timestamps();
        });

        Schema::create('sales_commission_slab_audits', function (Blueprint $table) {
            $table->id();
            $table->string('action', 20)->comment('created | updated | deleted');
            $table->unsignedBigInteger('slab_id')->nullable()->comment('ID of the slab affected');
            $table->json('slab_data')->nullable()->comment('Snapshot of the slab row at the time of the action');
            $table->string('performed_by')->nullable()->comment('Name or identifier of the admin who performed the action');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_commission_slab_audits');
        Schema::dropIfExists('sales_commission_slabs');
    }
};
