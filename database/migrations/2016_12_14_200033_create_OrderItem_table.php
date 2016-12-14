<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('OrderItem', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('externalId')->index()->nullable()->default(NULL);

            $table->integer('orderId')->unsigned()->index();
            $table->foreign('orderId')->references('id')->on('Order');


            //  Boilerplate
            $table->integer('statusId')->unsigned()->index();

            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('OrderItem');
    }
}
