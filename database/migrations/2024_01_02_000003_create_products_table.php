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
        Schema::create('products', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255);
            $table->string('name_french', 255);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('price_euro', 10, 2)->nullable();
            $table->decimal('price_gbp', 10, 2)->nullable();
            $table->decimal('price_usd', 10, 2)->nullable();
            $table->string('short_description', 255)->nullable();
            $table->string('short_description_french', 255)->nullable();
            $table->text('full_description')->nullable();
            $table->text('full_description_french')->nullable();
            $table->tinyInteger('is_today_deal')->default(0);
            $table->date('is_today_deal_date')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-is Active 0-Inactive');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->tinyInteger('menu_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->integer('sub_category_id')->default(0);
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('is_bestseller')->default(0);
            $table->tinyInteger('is_special')->default(0);
            $table->tinyInteger('is_stock')->default(1);
            $table->tinyInteger('poster_plans')->default(0);
            $table->tinyInteger('banners_frames')->default(0);
            $table->tinyInteger('cards_invites')->default(0);
            $table->tinyInteger('photo_gifts')->default(0);
            $table->tinyInteger('cart_name')->default(0);
            $table->tinyInteger('catalog')->default(0);
            $table->tinyInteger('brochure')->default(0);
            $table->tinyInteger('is_printed_product')->default(0);
            $table->integer('total_stock')->default(0);
            $table->mediumInteger('discount')->default(0);
            $table->string('product_image', 200)->nullable();
            $table->string('code', 50)->nullable();
            $table->string('code_french', 50)->nullable();
            $table->string('brand', 50)->nullable();
            $table->integer('reviews')->default(0);
            $table->integer('rating')->default(0);
            $table->integer('total_visited')->default(0);
            $table->decimal('delivery_charge', 10, 0)->default(0);
            $table->tinyInteger('is_bestdeal')->default(0);
            $table->tinyInteger('product_type')->default(2)->comment('1-Custum 2-uncutum');
            $table->integer('min_order_quantity');
            $table->integer('discount_id')->default(0);
            $table->tinyInteger('free_shipping')->default(1)->comment('1- is free 2 - not free');
            $table->string('store_id', 250)->default('1,2');
            $table->string('product_tag', 250)->nullable();
            $table->tinyInteger('add_length_width')->default(0);
            $table->decimal('min_length', 10, 1)->nullable();
            $table->decimal('max_length', 10, 1)->nullable();
            $table->decimal('min_width', 10, 1)->nullable();
            $table->decimal('max_width', 10, 1)->nullable();
            $table->decimal('min_length_min_width_price', 10, 4)->nullable();
            $table->string('length_width_pages_type', 10)->default('dropdown');
            $table->integer('length_width_min_quantity')->default(25);
            $table->integer('length_width_max_quantity')->default(5000);
            $table->tinyInteger('length_width_quantity_show')->default(1);
            $table->decimal('length_width_unit_price_black', 10, 4)->nullable();
            $table->decimal('length_width_price_color', 10, 4)->nullable();
            $table->tinyInteger('length_width_color_show')->default(0);
            $table->tinyInteger('votre_text')->default(0);
            $table->tinyInteger('recto_verso')->default(0);
            $table->integer('recto_verso_price')->default(0);
            $table->tinyInteger('page_add_length_width')->default(0);
            $table->decimal('page_min_length', 10, 1)->nullable();
            $table->decimal('page_max_length', 10, 1)->nullable();
            $table->decimal('page_min_width', 10, 1)->nullable();
            $table->decimal('page_max_width', 10, 1)->nullable();
            $table->decimal('page_min_length_min_width_price', 10, 4)->nullable();
            $table->string('page_length_width_pages_type', 10)->default('dropdown');
            $table->tinyInteger('page_length_width_pages_show')->default(1);
            $table->string('page_length_width_sheets_type', 10)->default('dropdown');
            $table->string('page_length_width_quantity_type', 10)->default('input');
            $table->tinyInteger('page_length_width_sheets_show')->default(0);
            $table->decimal('page_length_width_price_color', 10, 4)->nullable();
            $table->decimal('page_length_width_price_black', 10, 4)->nullable();
            $table->tinyInteger('page_length_width_color_show')->default(0);
            $table->integer('page_length_width_min_quantity')->default(25);
            $table->integer('page_length_width_max_quantity')->default(5000);
            $table->integer('page_length_width_quantity_show')->default(1);
            $table->tinyInteger('call')->default(0);
            $table->string('phone_number', 20)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('model_french', 100)->nullable();
            $table->tinyInteger('depth_add_length_width')->default(0);
            $table->decimal('min_depth', 10, 1)->nullable();
            $table->decimal('max_depth', 10, 1)->nullable();
            $table->decimal('depth_min_length', 10, 1)->nullable();
            $table->decimal('depth_min_width', 10, 1)->nullable();
            $table->decimal('depth_max_width', 10, 1)->nullable();
            $table->decimal('depth_width_length_price', 10, 4)->nullable();
            $table->string('depth_width_length_type', 20)->default('input');
            $table->string('depth_width_length_quantity_show', 20)->default('1');
            $table->decimal('depth_max_length', 10, 1)->nullable();
            $table->integer('depth_min_quantity')->default(25);
            $table->integer('depth_max_quantity')->default(5000);
            $table->decimal('depth_price_color', 10, 4)->nullable();
            $table->decimal('depth_unit_price_black', 10, 4)->nullable();
            $table->tinyInteger('depth_color_show')->default(0);
            $table->decimal('shipping_box_length', 10, 2)->nullable();
            $table->decimal('shipping_box_width', 10, 2)->nullable();
            $table->decimal('shipping_box_height', 10, 2)->nullable();
            $table->decimal('shipping_box_weight', 10, 2)->nullable();
            $table->unsignedInteger('use_custom_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
