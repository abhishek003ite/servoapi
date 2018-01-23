<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Payment;

class CreatePaymentsTable2 extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('order_id')->unsigned()->nullable();
            $table->string('gateway', 100);
            $table->string('transaction_id', 100);
            $table->decimal('amount', 20, 2);
            $table->string('status', 20)->default(Payment::getDefaultStatus());
            $table->string('comments', 2000)->nullable();

            $table->foreign('order_id')->references('id')
                  ->on('orders')->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
