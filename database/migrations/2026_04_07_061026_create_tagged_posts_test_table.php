<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagged_posts_test', function (Blueprint $table) {
            $table->bigInteger('id', true, false)->primary();
            $table->string('instagram_post_id', 100)->nullable()->unique();
            $table->text('post_url')->nullable();
            $table->string('username')->nullable();
            $table->longText('caption')->nullable();
            $table->text('hashtags')->nullable();
            $table->text('mentions')->nullable();
            $table->dateTime('scraped_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagged_posts_test');
    }
};
