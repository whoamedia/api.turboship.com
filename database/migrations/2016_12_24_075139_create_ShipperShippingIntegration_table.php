<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipperShippingIntegrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ShipperShippingIntegration', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('shipperId')->unsigned()->index();
            $table->foreign('shipperId')->references('id')->on('Shipper');

            $table->string('symbol')->index();

            $table->unique(['clientId', 'symbol']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ShipperShippingIntegration');
    }
}
