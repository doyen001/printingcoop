<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 255)->nullable();
            $table->string('title_french', 255)->nullable();
            $table->string('blog_slug', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_french')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->tinyInteger('populer')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('store_id', 100)->default('1,2,3,4,5');
            
            $table->index('blog_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
