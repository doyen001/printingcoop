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
        Schema::create('states', function (Blueprint $table) {
            $table->mediumInteger('id', true)->unsigned();
            $table->string('name', 255);
            $table->mediumInteger('country_id')->unsigned();
            $table->char('country_code', 2);
            $table->string('fips_code', 255)->nullable();
            $table->string('iso2', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId', 255)->nullable()->comment('Rapid API GeoDB Cities');
            $table->integer('clover_mode')->nullable()->default(0);
            $table->string('clover_sandbox_api_key', 255)->nullable();
            $table->string('clover_sandbox_secret', 255)->nullable();
            $table->string('clover_api_key', 255)->nullable();
            $table->string('clover_secret', 255)->nullable();
            
            $table->index('country_id', 'country_region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
