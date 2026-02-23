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
        Schema::create('countries', function (Blueprint $table) {
            $table->mediumInteger('id', true)->unsigned();
            $table->string('name', 100);
            $table->char('iso3', 3)->nullable();
            $table->char('iso2', 2)->nullable();
            $table->string('phonecode', 255)->nullable();
            $table->string('capital', 255)->nullable();
            $table->string('currency', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId', 255)->nullable()->comment('Rapid API GeoDB Cities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
