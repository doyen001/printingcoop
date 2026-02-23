<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->nullable();
            $table->string('discount_type', 20)->default('discount_percent');
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->datetime('discount_valid_from')->nullable();
            $table->datetime('discount_valid_to')->nullable();
            $table->mediumInteger('discount_requirement_quantity')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->datetime('created')->nullable();
            $table->datetime('updated')->nullable();
            $table->mediumInteger('discount_code_limit')->nullable();
            
            // Add indexes
            $table->index('code');
            $table->index('status');
            $table->index('discount_valid_from');
            $table->index('discount_valid_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
};
