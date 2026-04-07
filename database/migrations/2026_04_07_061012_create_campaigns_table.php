<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id');
            $table->string('title', 45);
            $table->longText('descriptions')->nullable();
            $table->longText('guidelines')->nullable();
            $table->decimal('coins', 10, 2)->default(0);
            $table->text('tags')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->longText('images')->nullable();
            $table->text('thumbnail')->nullable();
            $table->enum('status', [
                'active', 'inactive', 'pending', 'violated', 'live', 'completed',
                'paused', 'stopped', 'rejected', 'accepted',
            ])->default('pending');
            $table->string('city', 45)->nullable();
            $table->string('state', 45)->nullable();
            $table->enum('gender', ['male', 'female', 'both'])->default('male');
            $table->string('share_on', 45)->default('instagram,facebook');
            $table->integer('reward_per_user')->default(0);
            $table->integer('total_user_required')->default(0);
            $table->integer('number_of_post')->default(0);
            $table->decimal('daily_budget_cap', 10, 2)->default(0);
            $table->decimal('total_campaign_budget', 10, 2)->default(0);
            $table->string('age_range', 45);
            $table->integer('sale_id')->nullable();
            $table->enum('post_type', ['post', 'reel', 'story'])->default('post');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
