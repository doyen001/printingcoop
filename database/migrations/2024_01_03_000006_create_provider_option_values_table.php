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
        Schema::create('provider_option_values', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->unsignedBigInteger('option_id');
            $table->unsignedBigInteger('provider_option_value_id');
            $table->string('value', 255);
            $table->string('img_src', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('extra_turnaround_days')->nullable();
            
            $table->index('provider_option_value_id', 'option');
            $table->index(['option_id', 'provider_option_value_id', 'value'], 'option_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_option_values');
    }
};
