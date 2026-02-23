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
        Schema::create('currency', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('currency_name', 100)->nullable();
            $table->string('symbols', 250)->nullable();
            $table->string('code', 250)->nullable();
            $table->smallInteger('order')->default(1);
            $table->string('product_price_currency', 20)->default('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency');
    }
};
