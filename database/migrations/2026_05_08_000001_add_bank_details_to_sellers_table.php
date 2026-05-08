<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('bank_account_number', 30)->nullable()->after('billing_phone');
            $table->string('bank_ifsc_code', 20)->nullable()->after('bank_account_number');
            $table->string('bank_account_holder_name', 100)->nullable()->after('bank_ifsc_code');
            $table->enum('bank_account_type', ['savings', 'current'])->nullable()->after('bank_account_holder_name');
            $table->enum('bank_status', ['Not Submitted', 'Submitted', 'Verified', 'Rejected'])
                  ->default('Not Submitted')->after('bank_account_type');
            $table->text('bank_rejection_reason')->nullable()->after('bank_status');
        });
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn([
                'bank_account_number',
                'bank_ifsc_code',
                'bank_account_holder_name',
                'bank_account_type',
                'bank_status',
                'bank_rejection_reason',
            ]);
        });
    }
};
