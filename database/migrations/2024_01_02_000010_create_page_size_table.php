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
        Schema::create('page_size', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('name_french', 200);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('show_page_size')->default(1);
            $table->integer('set_order')->default(0);
            $table->integer('total_page')->nullable();
            $table->timestamps();
            
            $table->index(['status']);
            $table->index(['show_page_size']);
            $table->index(['set_order']);
            $table->index(['total_page']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_size');
    }
};
