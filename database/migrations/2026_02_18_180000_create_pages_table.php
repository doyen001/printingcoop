<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable();
            $table->string('title', 100)->nullable();
            $table->string('title_french', 100)->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_french')->nullable();
            $table->longText('html')->nullable();
            $table->datetime('created')->nullable();
            $table->datetime('updated')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->smallInteger('shortOrder')->default(0);
            $table->string('slug', 200)->nullable();
            $table->tinyInteger('display_on_footer')->default(1);
            $table->tinyInteger('display_on_top_menu')->default(1);
            $table->tinyInteger('display_on_footer_last_menu')->default(0);
            $table->tinyInteger('main_store_id')->default(1);
            $table->string('page_title_french', 150)->nullable();
            $table->string('page_title', 150)->nullable();
            $table->string('meta_description_content', 250)->nullable();
            $table->string('meta_description_content_french', 250)->nullable();
            $table->string('meta_keywords_content', 250)->nullable();
            $table->string('meta_keywords_content_french', 250)->nullable();
            
            // Indexes
            $table->index('status');
            $table->index('shortOrder');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
