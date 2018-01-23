<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Order;

class CreateOrdersTable2 extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('customer_id')->unsigned();
            $table->integer('partner_id')->unsigned();

            $table->string('status', 20)->default(Order::getDefaultStatus());
            $table->string('paid', 20)->default('no');

            $table->decimal('visitation_charges', 20, 2)->default(0); 
            $table->decimal('taxes', 20, 2)->default(0);
            $table->decimal('charges', 20, 2)->default(0);
            $table->decimal('discount', 20, 2)->default(0);
            $table->decimal('total', 20, 2)->default(0);

            $table->foreign('customer_id')->references('id')
                  ->on('customers')->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('partner_id')->references('id')
                  ->on('partners')->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
