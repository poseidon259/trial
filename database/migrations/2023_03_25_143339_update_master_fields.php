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
            $table->decimal('sale_price', 12, 2)->nullable()->change();
            $table->decimal('origin_price', 12, 2)->nullable()->change();
            $table->integer('stock')->nullable()->change();
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
            $table->decimal('sale_price', 12, 2)->nullable(false)->change();
            $table->decimal('origin_price', 12, 2)->nullable(false)->change();
            $table->integer('stock')->nullable(false)->change();
        });
    }
};
