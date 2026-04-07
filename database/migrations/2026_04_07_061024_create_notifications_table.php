<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50);
            $table->mediumText('description')->nullable();
            $table->string('user_type')->nullable();
            $table->mediumText('image')->nullable();
            $table->boolean('status')->default(true)->comment('0 = inactive, 1 = active');
            $table->integer('notification_count')->default(0);
            $table->enum('type', ['user', 'sale', 'brand'])->default('user');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
