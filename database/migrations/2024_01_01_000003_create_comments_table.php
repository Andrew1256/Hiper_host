<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->index();
            $table->foreign('post_id', 'fk_comments_post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->string('name', 50);
            $table->text('body');
            $table->string('ip_address', 45);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
