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
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable();
            $table->string('name_french', 50)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-is Active 0-Inactive');
            $table->datetime('created')->nullable();
            $table->datetime('updated')->nullable();
            $table->integer('menu_id')->nullable();
            $table->integer('category_id')->default(0);
            $table->integer('sub_category_order')->notNull();
            $table->string('image', 250)->nullable();
            $table->text('sub_category_dispersion')->nullable();
            $table->text('sub_category_dispersion_french')->nullable();
            $table->tinyInteger('show_main_menu')->default(1);
            $table->longText('page_title')->nullable();
            $table->longText('page_title_french')->nullable();
            $table->longText('meta_description_content')->nullable();
            $table->longText('meta_description_content_french')->nullable();
            $table->longText('meta_keywords_content')->nullable();
            $table->longText('meta_keywords_content_french')->nullable();
            $table->string('subcategory_slug', 255)->nullable();
            
            // Add indexes
            $table->index('category_id');
            $table->index('status');
            $table->index('sub_category_order');
            $table->index('show_main_menu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_categories');
    }
};
