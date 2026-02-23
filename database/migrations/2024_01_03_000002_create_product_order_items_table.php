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
        Schema::create('product_order_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('product_id')->default(0);
            $table->integer('order_id')->nullable();
            $table->tinyInteger('personailise')->default(0);
            $table->string('personailise_image', 250)->nullable();
            $table->string('name', 255);
            $table->string('name_french', 250)->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('short_description', 250)->nullable();
            $table->string('short_description_french', 250)->nullable();
            $table->text('full_description')->nullable();
            $table->text('full_description_french')->nullable();
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->mediumInteger('discount')->default(0);
            $table->string('product_image', 200)->nullable();
            $table->string('code', 50)->nullable();
            $table->string('brand', 50)->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('delivery_charge', 10, 2)->default(0.00);
            $table->integer('total_stock')->default(0);
            $table->text('cart_images')->nullable();
            $table->text('attribute_ids')->nullable();
            $table->text('product_size')->nullable();
            $table->text('product_width_length')->nullable();
            $table->string('votre_text', 250)->nullable();
            $table->string('recto_verso', 10)->nullable();
            $table->text('page_product_width_length')->nullable();
            $table->text('product_depth_length_width')->nullable();
            $table->decimal('shipping_box_length', 10, 2)->nullable();
            $table->decimal('shipping_box_width', 10, 2)->nullable();
            $table->decimal('shipping_box_height', 10, 2)->nullable();
            $table->decimal('shipping_box_weight', 10, 2)->nullable();
            
            $table->index('product_id');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_order_items');
    }
};
