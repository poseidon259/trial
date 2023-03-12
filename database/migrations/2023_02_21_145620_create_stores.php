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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('manager_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->bigInteger('province_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('ward_id')->nullable();
            $table->string('house_number')->nullable();
            $table->text('description_list')->nullable();
            $table->text('description_detail')->nullable();
            $table->string('logo')->nullable();
            $table->string('background_image')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('stores');
    }
};
