<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('partner_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('customer_id')->unsigned();
            $table->integer('partner_id')->unsigned();
            $table->unique(['customer_id', 'partner_id']);

            $table->decimal('rating', 2, 1)->default(0.5);
            $table->string('comments', 2000)->nullable();

            $table->foreign('customer_id')->references('id')
                  ->on('customers')->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('partner_id')->references('id')
                  ->on('partners')->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner_ratings');
    }
}
