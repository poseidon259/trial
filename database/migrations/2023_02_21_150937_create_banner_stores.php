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
        Schema::create('banner_stores', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('link_url')->nullable();
            $table->tinyInteger('sort')->nullable();
            $table->tinyInteger('display')->nullable();
            $table->bigInteger('store_id');
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
        Schema::dropIfExists('banner_stores');
    }
};
