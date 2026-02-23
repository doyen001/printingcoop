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
        Schema::create('banners', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('menu_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('name_french', 255)->nullable();
            $table->string('short_description', 150)->nullable();
            $table->string('short_description_french', 150)->nullable();
            $table->text('full_description')->nullable();
            $table->text('full_description_french')->nullable();
            $table->string('banner_image', 250)->nullable();
            $table->string('banner_image_french', 250)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-is Active 0-Inactive');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->integer('main_store_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
