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
        Schema::create('configurations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('main_store_id')->nullable();
            
            // Contact information
            $table->string('contact_no', 50)->nullable();
            $table->string('contact_no_french', 50)->nullable();
            $table->string('office_timing', 255)->nullable();
            $table->string('office_timing_french', 255)->nullable();
            
            // Logo and branding
            $table->string('logo_image', 255)->nullable();
            $table->string('logo_image_french', 255)->nullable();
            $table->string('log_alt_teg', 255)->nullable();
            $table->string('log_alt_teg_french', 255)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('french_favicon', 255)->nullable();
            
            // Announcements
            $table->text('announcement')->nullable();
            $table->text('announcement_french')->nullable();
            
            // Additional settings (JSON)
            $table->json('settings')->nullable();
            
            $table->timestamps();
            
            // Foreign key
            $table->foreign('main_store_id')->references('id')->on('stores')->onDelete('cascade');
            
            // Index
            $table->index('main_store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
