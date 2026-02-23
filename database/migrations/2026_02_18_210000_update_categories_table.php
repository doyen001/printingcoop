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
        Schema::table('categories', function (Blueprint $table) {
            // Add missing fields from CI project
            $table->integer('menu_id')->nullable()->after('status');
            $table->integer('category_order')->notNull()->default(0)->after('menu_id');
            $table->string('category_slug', 255)->nullable()->after('category_order');
            $table->string('image', 250)->nullable()->after('category_slug');
            $table->text('category_dispersion')->nullable()->after('image');
            $table->text('category_dispersion_french')->nullable()->after('category_dispersion');
            $table->tinyInteger('show_main_menu')->default(1)->after('category_dispersion_french');
            $table->tinyInteger('show_our_printed_product')->default(1)->after('show_main_menu');
            $table->tinyInteger('show_footer_menu')->default(1)->after('show_our_printed_product');
            $table->string('image_french', 250)->nullable()->after('show_footer_menu');
            $table->string('store_id', 50)->default('1,2,3,4')->after('image_french');
            $table->string('page_title', 250)->nullable()->after('store_id');
            $table->string('page_title_french', 250)->nullable()->after('page_title');
            $table->string('meta_description_content', 250)->nullable()->after('page_title_french');
            $table->string('meta_description_content_french', 250)->nullable()->after('meta_description_content');
            $table->string('meta_keywords_content', 250)->nullable()->after('meta_description_content_french');
            $table->string('meta_keywords_content_french', 250)->nullable()->after('meta_keywords_content');
            
            // Add indexes
            $table->index('category_order');
            $table->index('status');
            $table->index('show_main_menu');
            $table->index('show_footer_menu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn([
                'menu_id',
                'category_order',
                'category_slug',
                'image',
                'category_dispersion',
                'category_dispersion_french',
                'show_main_menu',
                'show_our_printed_product',
                'show_footer_menu',
                'image_french',
                'store_id',
                'page_title',
                'page_title_french',
                'meta_description_content',
                'meta_description_content_french',
                'meta_keywords_content',
                'meta_keywords_content_french'
            ]);
        });
    }
};
