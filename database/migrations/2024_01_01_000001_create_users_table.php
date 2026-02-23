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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 255);
            $table->string('fname', 20)->nullable();
            $table->string('lname', 20)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1-is Active 0-Inactive');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('role', 10)->default('customer');
            $table->string('profile_pic', 100)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->string('last_login_ip', 20)->nullable();
            $table->tinyInteger('email_verification')->default(0)->comment('0-unverifid,1-verifid ');
            $table->string('company_name', 250)->nullable();
            $table->string('responsible_name', 250)->nullable();
            $table->string('cp', 250)->nullable();
            $table->string('active_area', 250);
            $table->string('address', 250)->nullable();
            $table->string('country', 250)->nullable();
            $table->string('region', 250)->nullable();
            $table->string('city', 250)->nullable();
            $table->string('zip_code', 250)->nullable();
            $table->string('request', 250)->nullable();
            $table->tinyInteger('user_type')->default(1)->comment('1-normal 2-Pipred');
            $table->tinyInteger('preferred_status')->default(0)->comment('0 activre 1-active');
            $table->integer('store_id')->nullable()->default(1);
            
            $table->index('store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
