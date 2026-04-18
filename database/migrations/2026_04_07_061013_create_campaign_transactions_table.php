<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('campaign_transactions')) {
            return;
        }

        Schema::create('campaign_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->integer('user_id');
            $table->enum('shared_on', ['instagram', 'facebook'])->default('instagram');
            $table->enum('status', ['pending', 'completed', 'active', 'deleted', 'approved', 'rejected'])->default('pending');
            $table->decimal('earning', 10, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->mediumText('post_url')->nullable();
            $table->decimal('likes', 10, 2)->default(0);
            $table->decimal('comments', 10, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_transactions');
    }
};
