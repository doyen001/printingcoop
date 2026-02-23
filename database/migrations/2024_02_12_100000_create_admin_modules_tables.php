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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->integer('order')->default(0);
            $table->string('url')->nullable();
            $table->boolean('status')->default(true);
            $table->string('class')->nullable();
            $table->timestamps();
            
            $table->index(['status']);
            $table->index(['order', 'module_name']);
        });

        Schema::create('sub_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->string('sub_module_name');
            $table->integer('order')->default(0);
            $table->string('url');
            $table->string('class')->nullable();
            $table->string('action')->nullable();
            $table->boolean('show_menu')->default(true);
            $table->boolean('status')->default(true);
            $table->string('sub_module_class')->nullable();
            $table->timestamps();
            
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->index(['module_id', 'status']);
        });

        Schema::create('admin_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('module_id');
            $table->timestamps();
            
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->unique(['admin_id', 'module_id']);
        });

        Schema::create('admin_sub_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('sub_module_id');
            $table->timestamps();
            
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('sub_module_id')->references('id')->on('sub_modules')->onDelete('cascade');
            $table->unique(['admin_id', 'module_id', 'sub_module_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_sub_modules');
        Schema::dropIfExists('admin_modules');
        Schema::dropIfExists('sub_modules');
        Schema::dropIfExists('modules');
    }
};
