<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_campaign_activity_logs')) {
            return;
        }

        Schema::create('user_campaign_activity_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('campaigns_id');
            $table->unsignedInteger('user_id');
            $table->string('name', 150);
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index('campaigns_id');
            $table->index('user_id');
            $table->index('name');
            $table->index(['campaigns_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_campaign_activity_logs');
    }
};
