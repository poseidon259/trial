<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->text('note')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->bigInteger('province_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('ward_id')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->double('sub_total', 12, 2)->nullable();
            $table->double('total', 12, 2)->nullable();
            $table->double('discount', 12, 2)->nullable();
            $table->double('delivery_fee', 12, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->date('birthday')->nullable();
            $table->tinyInteger('status');
            $table->tinyInteger('payment_method')->nullable();
            $table->string('shipping_time')->nullable();
            $table->tinyInteger('address_type')->nullable();
            $table->bigInteger('user_id');
            $table->tinyInteger('refund_status')->nullable();
            $table->double('discount_freeship')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
