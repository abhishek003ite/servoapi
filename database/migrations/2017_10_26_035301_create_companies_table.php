<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('agree_best_knowledge', 5)->default('no');
            $table->string('agree_terms_conditions', 5)->default('no');
            
            $table->string('account_holder', 50)->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('ifsc_code', 12)->nullable();
            $table->string('pan_number', 12)->nullable();
            $table->string('gst_number', 20)->nullable();
            $table->string('director_name_1', 50)->nullable();
            $table->string('director_pan_1', 12)->nullable();
            $table->string('director_name_2', 50)->nullable();
            $table->string('director_pan_2', 12)->nullable();
            $table->string('certificate_incorporation', 1000)->nullable();

            $table->integer('partner_id')->unsigned();
            $table->foreign('partner_id')->references('id')
                  ->on('partners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
