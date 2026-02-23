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
        Schema::create('product_size', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('size_id')->nullable();
            $table->integer('qty')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('product_id')->nullable();
            $table->float('price', 10, 2)->nullable();
            $table->string('ncr_number_parts', 250)->nullable();
            $table->string('ncr_number_parts_french', 250)->nullable();
            $table->decimal('ncr_number_part_price', 10, 2)->default(0.00);
            $table->string('stock', 250)->nullable();
            $table->string('stock_french', 250)->nullable();
            $table->decimal('stock_extra_price', 10, 2)->default(0.00);
            $table->string('paper_quality', 250)->nullable();
            $table->string('paper_quality_french', 250)->nullable();
            $table->decimal('paper_quality_extra_price', 10, 2)->default(0.00);
            $table->string('color', 250)->nullable();
            $table->string('color_french', 250)->nullable();
            $table->decimal('color_extra_price', 10, 2)->default(0.00);
            $table->string('diameter', 250)->nullable();
            $table->string('diameter_french', 250)->nullable();
            $table->decimal('diameter_extra_price', 10, 2)->default(0.00);
            $table->decimal('extra_price', 10, 2)->default(0.00);
            $table->string('shape_paper', 250)->nullable();
            $table->string('shape_paper_french', 250)->nullable();
            $table->decimal('shape_paper_extra_price', 10, 2)->default(0.00);
            $table->string('grommets', 250)->nullable();
            $table->string('grommets_french', 250)->nullable();
            $table->decimal('grommets_extra_price', 10, 2)->default(0.00);
            
            $table->index('color_french');
            $table->index('diameter_french');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_size');
    }
};
