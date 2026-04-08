<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_campaign_skips', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('campaign_id');
            $table->timestamps();

            $table->unique(['user_id', 'campaign_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_campaign_skips');
    }
};
