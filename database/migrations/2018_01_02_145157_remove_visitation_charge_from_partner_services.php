<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveVisitationChargeFromPartnerServices extends Migration
{
    public function up()
    {
        Schema::table('partner_services', function (Blueprint $table) {
            $table->dropColumn('visitation_price');
        });
    }

    public function down()
    {
        //
    }
}
