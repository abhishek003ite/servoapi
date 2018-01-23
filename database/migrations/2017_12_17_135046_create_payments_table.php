<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('order_id')->unsigned();
            $table->string('gateway', 100);
            $table->string('tracking_code', 100);
            $table->decimal('amount', 20, 2);
            $table->string('comments', 2000)->nullable();

            $table->foreign('order_id')->references('id')
                  ->on('orders')->onDelete('cascade')
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
        Schema::dropIfExists('payments');
    }
}
