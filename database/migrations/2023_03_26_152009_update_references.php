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
        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('category_id')->unsigned()->change();
            $table->bigInteger('category_child_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('category_child_id')->references('id')->on('category_child');
        });

        Schema::table('product_information', function (Blueprint $table) {
            $table->bigInteger('product_id')->unsigned()->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->bigInteger('product_id')->unsigned()->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['category_child_id']);
            $table->dropColumn('category_child_id');
        });

        Schema::table('product_information', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
    }
};
