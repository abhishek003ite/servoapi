<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('is_customer', 10)->default('no');
            $table->string('is_partner', 10)->default('no');
            $table->string('is_admin', 10)->default('no');

            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            
            $table->string('mobile_country_code', 3)->nullable();
            $table->string('mobile_number', 30)->nullable();
            $table->string('is_mobile_verified')->default('no');
            
            $table->string('email', 100)->unique();
            $table->string('email_confirmation_code')->nullable();
            $table->string('is_email_confirmed')->default('no');
            
            $table->string('password', 200)->nullable();
            $table->string('forgot_password_token', 200)->nullable();
            
            $table->string('api_token', 60)->nullable();
            $table->timestamp('api_token_valid_till')->nullable();
            
            $table->integer('time_zone_id')->unsigned()->nullable();
            $table->foreign('time_zone_id')->references('id')
                    ->on('time_zones')->onDelete('set null');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
