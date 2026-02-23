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
        Schema::create('product_personalise', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('product_Id');
            $table->longText('text_field');
            $table->longText('paragraph');
            $table->integer('image_upload');
            $table->text('color');
            $table->tinyInteger('writeown')->default(0);
            $table->integer('writeown_paragraph_char');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_personalise');
    }
};
