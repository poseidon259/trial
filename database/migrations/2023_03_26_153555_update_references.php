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
        Schema::table('master_fields', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('sale_price');
            $table->dropColumn('origin_price');
            $table->dropColumn('stock');
            $table->dropColumn('product_code');
            $table->bigInteger('product_id')->unsigned()->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::create('child_master_fields', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('master_field_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->string('name');
            $table->decimal('sale_price', 12, 2);
            $table->decimal('original_price', 12, 2);
            $table->bigInteger('stock')->unsigned();
            $table->string('product_code')->nullable();
            $table->foreign('master_field_id')->references('id')->on('master_fields')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::table('master_fields', function (Blueprint $table) {
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('origin_price', 12, 2)->nullable();
            $table->bigInteger('stock')->unsigned()->nullable();
            $table->string('product_code')->nullable();
            $table->dropForeign(['product_id']);
        });

        Schema::dropIfExists('child_master_fields');
    }
};
