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
        Schema::create('categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 50)->nullable();
            $table->string('name_french', 50)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-is Active 0-Inactive');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->integer('menu_id')->nullable();
            $table->integer('category_order');
            $table->string('image', 250)->nullable();
            $table->text('category_dispersion')->nullable();
            $table->text('category_dispersion_french')->nullable();
            $table->tinyInteger('show_main_menu')->default(1);
            $table->tinyInteger('show_our_printed_product')->default(1);
            $table->tinyInteger('show_footer_menu')->default(1);
            $table->string('image_french', 250)->nullable();
            $table->string('store_id', 50)->default('1,2,3,4');
            $table->string('page_title', 250)->nullable();
            $table->string('page_title_french', 250)->nullable();
            $table->string('meta_description_content', 250)->nullable();
            $table->string('meta_description_content_french', 250)->nullable();
            $table->string('meta_keywords_content', 250)->nullable();
            $table->string('meta_keywords_content_french', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
