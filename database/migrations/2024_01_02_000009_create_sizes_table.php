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
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('size_name', 250)->nullable();
            $table->string('size_name_french', 250)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('set_order')->default(0);
            $table->timestamps();
            
            $table->index('size_name');
            $table->index('status');
            $table->index('set_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
