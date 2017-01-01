<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentImageTable extends Migration
{

    public function up()
    {
        Schema::create('ShipmentImage', function (Blueprint $table)
        {
            $table->integer('shipmentId')->unsigned()->index();
            $table->foreign('shipmentId')->references('id')->on('Shipment');


            $table->integer('imageId')->unsigned()->index();
            $table->foreign('imageId')->references('id')->on('Image');

            $table->primary(['shipmentId', 'imageId']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ShipmentImage');
    }
}
