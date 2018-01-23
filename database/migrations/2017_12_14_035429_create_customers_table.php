<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('profile_photo', 500)->nullable();
            $table->decimal('lat', 20, 10)->nullable();
            $table->decimal('lng', 20, 10)->nullable();

            $table->string('is_active')->default('no');

            $table->integer('user_id')->unsigned();
            $table->integer('address_id')->unsigned();

            $table->foreign('user_id')->references('id')
                  ->on('users')->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('address_id')->references('id')
                  ->on('addresses')->onDelete('cascade')
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
        Schema::dropIfExists('customers');
    }
}
