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
        Schema::create('provider_products', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('provider_product_id');
            $table->string('sku', 255);
            $table->string('name', 255);
            $table->string('category', 255);
            $table->tinyInteger('enabled')->default(0);
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedInteger('information_type')->default(0);
            $table->double('price_rate')->default(1.75);
            $table->tinyInteger('deleted')->default(0);
            $table->tinyInteger('updating')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->index(['provider_id', 'name'], 'name');
            $table->index(['provider_id', 'sku'], 'sku');
            $table->index(['provider_id', 'category'], 'category');
            $table->index(['provider_id', 'provider_product_id'], 'provider_product_id');
            $table->index(['provider_id', 'deleted'], 'deleted');
            $table->index(['provider_id', 'updating'], 'updating');
            $table->index(['provider_id', 'product_id'], 'product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_products');
    }
};
