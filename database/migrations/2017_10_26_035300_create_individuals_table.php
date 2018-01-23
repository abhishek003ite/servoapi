<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individuals', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            $table->string('account_holder_name', 200)->nullable();
            $table->string('bank_account_number', 30)->nullable();
            $table->string('ifsc_code', 20)->nullable();
            $table->string('pan_number', 20)->nullable();
            $table->string('gst_number', 20)->nullable();
            $table->string('aadhaar_number', 20)->nullable();

            $table->string('aadhaar_scan_front_file', 1000)->nullable();
            $table->string('aadhaar_scan_back_file', 1000)->nullable();

            $table->string('agree_best_knowledge', 5)->default('no');
            $table->string('agree_terms_conditions', 5)->default('no');

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
        Schema::dropIfExists('individuals');
    }
}
