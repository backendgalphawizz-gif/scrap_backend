<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200);
            $table->text('description');
            $table->string('type', 45)->default('order');
            $table->string('type_id', 45)->default('-');
            $table->string('image', 45)->default('');
            $table->boolean('is_read')->default(false)->comment('0 = unread, 2 = Read');
            $table->integer('user_id');
            $table->timestamp('created_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_notifications');
    }
};
