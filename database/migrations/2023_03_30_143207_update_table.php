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
        Schema::table('order_items', function (Blueprint $table) {
            $table->bigInteger('master_field_id')->unsigned()->nullable();
            $table->bigInteger('child_master_field_id')->unsigned()->nullable();
            $table->foreign('master_field_id')->references('id')->on('master_fields');
            $table->foreign('child_master_field_id')->references('id')->on('child_master_fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['master_field_id']);
            $table->dropForeign(['child_field_id']);
            $table->dropColumn(['master_field_id', 'child_field_id']);
        });
    }
};
