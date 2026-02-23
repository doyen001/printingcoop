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
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 50)->nullable();
            $table->string('name_french', 50)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-is Active 0-Inactive');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->integer('menu_id')->nullable();
            $table->integer('category_id')->default(0);
            $table->integer('sub_category_order');
            $table->string('image', 250)->nullable();
            $table->text('sub_category_dispersion')->nullable();
            $table->text('sub_category_dispersion_french')->nullable();
            $table->tinyInteger('show_main_menu')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
