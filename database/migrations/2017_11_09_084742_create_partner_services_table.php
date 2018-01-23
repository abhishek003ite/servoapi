<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_services', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('partner_id')->unsigned();
            $table->integer('service_id')->unsigned();

            $table->decimal('visitation_price', 10, 2)->nullable();
            $table->decimal('service_price', 10, 2)->nullable();
            $table->string('details', 5000)->nullable();

            $table->foreign('partner_id')->references('id')
                ->on('partners')->onDelete('cascade');

            $table->foreign('service_id')->references('id')
                ->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner_services');
    }
}
