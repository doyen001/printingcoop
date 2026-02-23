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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('name_french', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('description_french', 255)->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_french')->nullable();
            $table->boolean('status')->default(1);
            $table->datetime('created')->nullable();
            $table->datetime('updated')->nullable();
            $table->integer('main_store_id')->default(1);
            $table->string('background_image', 250)->nullable();
            $table->string('french_background_image', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
};
