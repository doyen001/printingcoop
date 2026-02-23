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
        Schema::table('blocked_ips', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('blocked_ips', 'status')) {
                $table->tinyInteger('status')->default(1);
                $table->index('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blocked_ips', function (Blueprint $table) {
            // Drop status column if it exists
            if (Schema::hasColumn('blocked_ips', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }
        });
    }
};
