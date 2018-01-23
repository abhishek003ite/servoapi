<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescToTaxesAndChargesInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tax_details', 2000)
                  ->default('GST @ '. env('GST') . '%');
            $table->string('charges_details', 2000)
                  ->default('Convenience charge @ '. env('CONV_CHARGE') . '%');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
