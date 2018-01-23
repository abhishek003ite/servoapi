<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('order_id')->unsigned();
            $table->integer('partner_id')->unsigned();

            $table->integer('partner_service_id')->unsigned()->nullable();
            $table->string('service_name', 100); // in case service is deleted some day
            $table->decimal('service_charges', 20, 2)->default(0);

            $table->foreign('order_id')->references('id')
                  ->on('orders')->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('partner_service_id')->references('id')
                  ->on('partner_services')->onDelete('set null')
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
        Schema::dropIfExists('order_items');
    }
}
