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
        Schema::create('addresses', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->default(0);
            $table->string('name', 64)->nullable();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->string('pin_code', 50)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-is Active 0-Inactive');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->string('mobile', 50)->default('0');
            $table->string('address', 150)->nullable();
            $table->string('city', 20)->nullable();
            $table->string('country', 10)->default('India');
            $table->smallInteger('state')->nullable();
            $table->string('landmark', 50)->nullable();
            $table->string('alternate_phone', 50)->nullable();
            $table->string('address_type', 20)->nullable();
            $table->tinyInteger('default_delivery_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
