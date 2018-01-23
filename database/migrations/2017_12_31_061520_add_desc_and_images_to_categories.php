<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescAndImagesToCategories extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('description', 2000)
                  ->default('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.')->nullable();
            $table->string('img_large', 500)->default('assets/large-icon.png')->nullable();
            $table->string('img_small', 500)->default('assets/square-icon.png')->nullable();
        });
    }

    public function down()
    {
        //
    }
}
