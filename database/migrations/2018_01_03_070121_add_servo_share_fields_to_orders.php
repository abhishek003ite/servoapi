<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServoShareFieldsToOrders extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('servo_service_charge_share', 10, 2)->default(0);
            $table->string('servo_service_charge_share_desc', 100)
                ->default(env('SERVO_SHARE_SERVICE_CHARGES_TYPE') . ' ' . 
                env('SERVO_SHARE_SERVICE_CHARGES'));
            $table->decimal('servo_visitation_charge_share', 10, 2)->default(0);
            $table->string('servo_visitation_charge_share_desc', 100)
                ->default(env('SERVO_SHARE_VISITATION_CHARGES_TYPE') . ' ' . 
                env('SERVO_SHARE_VISITATION_CHARGES'));
        });
    }

    public function down()
    {
        //
    }
}
