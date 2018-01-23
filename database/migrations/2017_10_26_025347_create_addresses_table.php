<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('building_num',200)->nullable();
            $table->string('street_address',200)->nullable();
            $table->string('landmark', 200)->nullable();
            $table->string('region',200)->nullable();
            $table->string('city',200)->nullable();
            $table->string('pincode',10)->nullable();
            $table->string('state',200)->nullable();
            $table->integer('country_id')->unsigned()->nullable();

            $table->decimal('lat', 20, 10)->nullable();
            $table->decimal('long', 20, 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
