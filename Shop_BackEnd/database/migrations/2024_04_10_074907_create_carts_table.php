<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('size_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('color_id')->references('id')->on('colors');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('size_id')->references('id')->on('sizes');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
