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
        Schema::create('product_orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('order_id', 50)->nullable();
            $table->integer('user_id')->default(0);
            $table->string('name', 20)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->decimal('total_sales_tax', 10, 2)->nullable();
            $table->decimal('sub_total_amount', 10, 2);
            $table->decimal('preffered_customer_discount', 10, 2);
            $table->tinyInteger('payment_status')->default(1)->comment('1- Pending,2-Success,3-failed');
            $table->string('payment_type', 20)->nullable();
            $table->string('payment_method', 20)->nullable();
            $table->decimal('delivery_charge', 10, 2)->default(0.00);
            $table->integer('total_items')->default(0);
            $table->string('billing_pin_code', 50)->nullable();
            $table->tinyInteger('status')->default(2)->comment('1-incomplete 2-new 3-process to delivery,4-Delivered 5-cancelled,6-failed');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->string('billing_name', 50)->nullable();
            $table->string('billing_mobile', 50)->default('');
            $table->string('billing_address', 150)->nullable();
            $table->string('billing_city', 20)->nullable();
            $table->integer('billing_country')->default(39);
            $table->integer('billing_state')->nullable();
            $table->string('billing_landmark', 50)->nullable();
            $table->bigInteger('billing_alternate_phone')->nullable();
            $table->string('billing_address_type', 20)->nullable();
            $table->string('transition_id', 150)->nullable();
            $table->string('shipping_pin_code', 50)->nullable();
            $table->string('shipping_name', 150)->nullable();
            $table->string('shipping_mobile', 50)->nullable();
            $table->string('shipping_address', 150)->nullable();
            $table->string('shipping_city', 20)->nullable();
            $table->integer('shipping_country')->default(39);
            $table->integer('shipping_state')->nullable();
            $table->string('shipping_landmark', 50)->nullable();
            $table->string('shipping_method', 255)->nullable();
            $table->bigInteger('shipping_alternate_phone')->nullable();
            $table->string('shipping_address_type', 20)->nullable();
            $table->string('transition_remark', 150)->nullable();
            $table->integer('delivery_address_id')->nullable();
            $table->tinyInteger('admin_delete')->default(1);
            $table->tinyInteger('user_delete')->default(1);
            $table->date('order_date')->nullable();
            $table->string('shipping_method_formate', 250)->nullable();
            $table->integer('store_id')->default(1);
            $table->integer('currency_id')->default(1);
            $table->decimal('coupon_discount_amount', 10, 2)->default(0.00);
            $table->string('coupon_code', 100)->nullable();
            $table->string('billing_company', 100)->nullable();
            $table->string('shipping_company', 100)->nullable();
            $table->string('order_comment', 250)->nullable();
            $table->integer('order_admin')->default(0);
            $table->string('payment_mode', 20)->default('live');
            $table->string('shipment_id', 100)->nullable();
            $table->string('tracking_number', 100)->nullable();
            $table->text('labels_regular')->nullable();
            $table->text('labels_thermal')->nullable();
            $table->text('shipment_data')->nullable();
            $table->decimal('flag_shiping_cost', 10, 2)->nullable();
            $table->text('paypal_responce')->nullable();
            
            $table->index('status');
            $table->index('order_date');
            $table->index('admin_delete');
            $table->index('user_id');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_orders');
    }
};
