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
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->bigInteger('role_id')->nullable();
            $table->bigInteger('store_id')->nullable();
            $table->string('postal_code')->nullable();
            $table->bigInteger('province_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('ward_id')->nullable();
            $table->string('house_number')->nullable();
            $table->string('phone_number')->nullable()->unique();
            $table->string('user_name')->nullable()->unique();
            $table->tinyInteger('gender')->nullable();
            $table->string('avatar')->nullable();
            $table->date('birthday')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('role_id');
            $table->dropColumn('store_id');
            $table->dropColumn('postal_code');
            $table->dropColumn('province_id');
            $table->dropColumn('district_id');
            $table->dropColumn('ward_id');
            $table->dropColumn('house_number');
            $table->dropColumn('gender');
            $table->dropColumn('avatar');
            $table->dropColumn('birthday');
            $table->dropColumn('status');
        });
    }
};
