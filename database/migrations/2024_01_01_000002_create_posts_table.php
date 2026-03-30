<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreign('user_id', 'fk_posts_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title', 70);
            $table->string('slug')->unique();
            $table->text('body');
            $table->string('image')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('publish_at')->nullable();
            $table->unsignedInteger('views_count')->default(0)->index();
            $table->unsignedInteger('comments_count')->default(0)->index();
            $table->timestamps();

            // Composite index for the scheduled publisher command
            $table->index(['status', 'publish_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
