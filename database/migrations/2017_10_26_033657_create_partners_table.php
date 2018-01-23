<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('is_active')->default('no');

            $table->string('profile_photo_file', 500)->nullable();
            $table->string('about', 1000)->nullable();

            $table->string('works_sundays')->default('no')->nullable();
            $table->string('works_saturdays')->default('no')->nullable();
            $table->string('works_mondays')->default('no')->nullable();
            $table->string('works_tuesdays')->default('no')->nullable();
            $table->string('works_wednesdays')->default('no')->nullable();
            $table->string('works_thursdays')->default('no')->nullable();
            $table->string('works_fridays')->default('no')->nullable();

            $table->time('work_timings_start')->nullable();
            $table->time('work_timings_end')->nullable();

            $table->decimal('service_location_lat', 20, 10)->nullable();
            $table->decimal('service_location_long', 20, 10)->nullable();
            $table->decimal('service_radius_km', 5, 2)->nullable();
            $table->string('profile_completed')->default('no');

            $table->integer('user_id')->unsigned();
            $table->integer('address_id')->unsigned()->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
}
