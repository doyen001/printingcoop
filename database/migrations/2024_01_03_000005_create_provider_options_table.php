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
        Schema::create('provider_options', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->unsignedBigInteger('provider_option_id')->nullable();
            $table->string('name', 255);
            $table->string('label', 255);
            $table->integer('type')->default(999);
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->string('html_type', 16)->nullable();
            $table->integer('sort_order')->default(0);
            
            $table->unique(['provider_id', 'provider_option_id'], 'option_id');
            $table->index('type');
            $table->index(['provider_id', 'attribute_id'], 'attribute_id');
            $table->index(['provider_id', 'name'], 'provider_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_options');
    }
};
