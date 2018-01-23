<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartnerVisitingCharges extends Migration
{
    public function up()
    {
        Schema::table('partners', function(Blueprint $table){
            $table->decimal('visitation_charges', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('partners', function(Blueprint $table){
            $table->dropColumn('visitation_charges');
        });
    }
}
