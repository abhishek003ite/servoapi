<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('cart_id')->unsigned();
            $table->integer('partner_service_id')->unsigned();

            $table->foreign('cart_id')->references('id')
                  ->on('carts')->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('partner_service_id')->references('id')
                  ->on('partner_services')->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
