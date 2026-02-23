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
        Schema::table('products', function (Blueprint $table) {
            // Add indexes for frequently queried columns
            $table->index(['status'], 'products_status_index');
            $table->index(['updated'], 'products_updated_index');
            $table->index(['category_id'], 'products_category_id_index');
            $table->index(['sub_category_id'], 'products_sub_category_id_index');
            $table->index(['status', 'updated'], 'products_status_updated_index');
            $table->index(['category_id', 'status'], 'products_category_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the indexes
            $table->dropIndex('products_status_index');
            $table->dropIndex('products_updated_index');
            $table->dropIndex('products_category_id_index');
            $table->dropIndex('products_sub_category_id_index');
            $table->dropIndex('products_status_updated_index');
            $table->dropIndex('products_category_status_index');
        });
    }
};
