<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ShipmentItem', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('shipmentId')->unsigned()->index();
            $table->foreign('shipmentId')->references('id')->on('Shipment');

            $table->integer('orderItemId')->unsigned()->index();
            $table->foreign('orderItemId')->references('id')->on('OrderItem');

            $table->integer('quantity')->unsigned()->index();
            $table->integer('quantityReserved')->unsigned()->index()->default(0);

        });

    }

    public function down()
    {
        Schema::drop('ShipmentItem');
    }

}
