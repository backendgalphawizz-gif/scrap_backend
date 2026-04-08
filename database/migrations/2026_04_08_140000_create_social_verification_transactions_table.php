<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_verification_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('platform', ['instagram', 'facebook']);
            $table->string('username', 100);
            $table->string('unique_code', 100);
            $table->enum('status', ['pending', 'verified', 'not_verified'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['status', 'platform']);
            $table->index('unique_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_verification_transactions');
    }
};
