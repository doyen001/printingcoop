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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->nullable();
            $table->string('name_french', 150)->nullable();
            $table->integer('tag_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->datetime('created')->nullable();
            $table->datetime('updated')->nullable();
            $table->string('image', 250)->nullable();
            $table->string('image_french', 250)->nullable();
            $table->tinyInteger('proudly_display_your_brand')->default(0);
            $table->tinyInteger('montreal_book_printing')->default(0);
            $table->tinyInteger('footer')->default(0);
            $table->string('font_class', 250)->default('la la-credit-card');
            $table->string('store_id', 30)->default('1,2,3,4');
            
            // Add indexes
            $table->index('tag_order');
            $table->index('status');
            $table->index('footer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
};
