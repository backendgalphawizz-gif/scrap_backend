<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('admin_notifications')) {
            return;
        }

        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            // Dot-notated event type: brand.registered, user.pan_submitted, etc.
            $table->string('type', 60);
            $table->string('title', 200);
            $table->text('message');
            // Direct link to the relevant admin page
            $table->string('link', 255)->nullable();
            // Polymorphic reference to the source record
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('related_type', 60)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['is_read', 'created_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
