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
        Schema::create('discounts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('code', 50)->nullable();
            $table->string('discount_type', 20)->default('discount_percent');
            $table->float('discount', 10, 2)->default(0.00);
            $table->dateTime('discount_valid_from')->nullable();
            $table->dateTime('discount_valid_to')->nullable();
            $table->mediumInteger('discount_requirement_quantity')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->mediumInteger('discount_code_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
