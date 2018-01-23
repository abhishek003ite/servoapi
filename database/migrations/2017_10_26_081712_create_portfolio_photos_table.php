<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfolioPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('file', 200);
            
            $table->integer('portfolio_id')->unsigned();
            $table->foreign('portfolio_id')->references('id')
                  ->on('portfolios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolio_photos');
    }
}
