<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facebook_posts_test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('post_url', 500)->nullable()->unique();
            $table->text('caption')->nullable();
            $table->text('hashtags')->nullable();
            $table->text('mentions')->nullable();
            $table->dateTime('scraped_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_posts_test');
    }
};
