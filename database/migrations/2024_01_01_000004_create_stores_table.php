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
        Schema::create('stores', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->nullable();
            $table->string('phone', 250)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('url', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('currency_id', 11)->nullable();
            $table->integer('langue_id')->nullable();
            $table->integer('shopping_id')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('default_currency_id')->default(1);
            $table->integer('stor_type')->default(1);
            $table->integer('main_store')->default(1);
            $table->integer('main_store_id')->nullable();
            $table->string('order_id_prefix', 30)->nullable();
            $table->tinyInteger('show_all_categories')->default(1);
            $table->tinyInteger('show_language_translation')->default(1);
            $table->text('email_footer_line')->nullable();
            $table->string('from_email', 150)->default('info@printing.coop');
            $table->string('admin_email1', 150)->default('info@printing.coop');
            $table->string('admin_email2', 150)->default('imprimeur.coop@gmail.com');
            $table->string('admin_email3', 150)->default('techbull.in@gmail.com');
            $table->string('email_template_logo', 250)->nullable();
            $table->string('paypal_business_email', 200)->default('imprimeur.coop@gmail.com');
            $table->string('paypal_payment_mode', 10)->default('sendbox');
            $table->string('paypal_sandbox_business_email', 100)->default('sb-ks2ro721209@business.example.com');
            $table->text('order_pdf_company')->nullable();
            $table->text('invoice_pdf_company')->nullable();
            $table->string('pdf_template_logo', 250)->nullable();
            $table->string('http_url', 200)->nullable();
            $table->string('website_name', 200)->nullable();
            $table->string('flag_ship', 10)->default('no');
            $table->integer('clover_mode')->nullable()->default(0);
            $table->string('clover_sandbox_api_key', 255)->nullable();
            $table->string('clover_sandbox_secret', 255)->nullable();
            $table->string('clover_api_key', 255)->nullable();
            $table->string('clover_secret', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
