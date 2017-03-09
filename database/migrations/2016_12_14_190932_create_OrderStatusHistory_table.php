<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStatusHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('OrderStatusHistory', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('orderId')->unsigned()->index();
            $table->foreign('orderId')->references('id')->on('Orders');


            //  Boilerplate
            $table->integer('statusId')->unsigned()->index();
            $table->foreign('statusId')->references('id')->on('OrderStatus');

            $table->integer('createdById')->unsigned()->index()->nullable()->default(NULL);
            $table->foreign('createdById')->references('id')->on('User');

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
        Schema::drop('OrderStatusHistory');
    }
}
