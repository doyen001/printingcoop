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
        Schema::create('product_images', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('image', 255);
            $table->tinyInteger('status')->default(1)->comment('1-is Active 0-Inactive');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->integer('product_id')->default(0);
            $table->tinyInteger('is_main_image')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
