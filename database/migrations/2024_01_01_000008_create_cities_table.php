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
        Schema::create('cities', function (Blueprint $table) {
            $table->mediumInteger('id', true)->unsigned();
            $table->string('name', 255);
            $table->mediumInteger('state_id')->unsigned();
            $table->string('state_code', 255);
            $table->mediumInteger('country_id')->unsigned();
            $table->char('country_code', 2);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_on')->useCurrent()->useCurrentOnUpdate();
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId', 255)->nullable()->comment('Rapid API GeoDB Cities');
            
            $table->index('state_id', 'cities_test_ibfk_1');
            $table->index('country_id', 'cities_test_ibfk_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
