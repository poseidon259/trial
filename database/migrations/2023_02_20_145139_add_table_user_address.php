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
        Schema::create('users_address', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('postal_code')->nullable();
            $table->bigInteger('province_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('ward_id')->nullable();
            $table->string('house_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->integer('user_id')->nullable();
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
        //
        Schema::dropIfExists('users_address');
    }
};
