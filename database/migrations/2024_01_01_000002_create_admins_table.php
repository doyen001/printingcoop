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
        Schema::create('admins', function (Blueprint $table) {
            $table->integer('id', true);
            $table->tinyInteger('status')->default(1)->comment('1-is Active 0-Inactive');
            $table->string('name', 255);
            $table->dateTime('updated')->nullable();
            $table->dateTime('created')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('username', 50)->nullable();
            $table->string('role', 10)->default('admin');
            $table->string('profile_pic', 100)->default('');
            $table->string('store_ids', 250)->default('');
            $table->string('address', 255)->default('');
            $table->string('mobile', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
