<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations.
     */
    public function up(): void
    {
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->string('contact_name', 255);
            $table->string('company_name', 255);
            $table->string('email', 255);
            $table->string('phone_number', 255);
            $table->string('street', 255);
            $table->string('city', 255);
            $table->string('province', 255);
            $table->string('country', 255);
            $table->string('postal_code', 255);
            $table->string('product_type', 255);
            $table->string('product_name', 255);
            $table->boolean('has_quote_form')->default(false);
            $table->boolean('same_quote_request')->default(false);
            $table->string('qty_1', 255);
            $table->string('qty_2', 255);
            $table->string('qty_3', 255);
            $table->string('more_qty', 255);
            $table->string('flat_size', 255);
            $table->string('finish_size', 255);
            $table->string('finish_size', 255);
            $table->string('paper_stock', 255);
            $table->timestamps();
            
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
