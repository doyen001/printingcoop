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
        Schema::create('tickets', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->text('message')->nullable();
            $table->dateTime('created');
            $table->dateTime('updated');
            $table->tinyInteger('status');
            $table->string('name', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('contact_no', 10);
            $table->string('subject', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
