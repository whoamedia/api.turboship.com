<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Order', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('externalId')->index();

            $table->integer('orderSourceId')->unsigned()->index();
            $table->foreign('orderSourceId')->references('id')->on('OrderSource');

            $table->integer('clientId')->unsigned()->index();
            $table->foreign('clientId')->references('id')->on('Client');


            //  Boilerplate
            $table->integer('statusId')->unsigned()->index();
            $table->foreign('statusId')->references('id')->on('OrderStatus');

            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();

            $table->unique(['externalId', 'orderSourceId', 'clientId']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Order');
    }

}
