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
        Schema::create('product_quantity', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('qty')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('product_id')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            
            $table->index(['product_id', 'qty'], 'qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_quantity');
    }
};
