<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->timestamps();

            $table->integer('customer_id')->unsigned();
            $table->integer('partner_id')->unsigned();
            
            $table->foreign('customer_id')->references('id')
                  ->on('customers')->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('partner_id')->references('id')
                  ->on('partners')->onDelete('cascade')
                  ->onUpdate('cascade');
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
