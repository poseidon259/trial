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
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('role_id')->unsigned()->change();
            $table->bigInteger('store_id')->unsigned()->nullable()->change();
            $table->bigInteger('province_id')->unsigned()->nullable()->change();
            $table->bigInteger('district_id')->unsigned()->nullable()->change();
            $table->bigInteger('ward_id')->unsigned()->nullable()->change();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('ward_id')->references('id')->on('wards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['store_id']);
            $table->dropForeign(['province_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['ward_id']);
        });
    }
};
