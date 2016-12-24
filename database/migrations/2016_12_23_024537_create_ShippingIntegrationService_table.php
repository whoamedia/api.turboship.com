<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingIntegrationServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ShippingIntegrationService', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 100);

            $table->integer('shippingIntegrationCarrierId')->unsigned()->index();
            $table->foreign('shippingIntegrationCarrierId')->references('id')->on('ShippingIntegrationCarrier');

            $table->integer('serviceId')->unsigned()->index();
            $table->foreign('serviceId')->references('id')->on('Service');


            $table->unique(['name', 'shippingIntegrationCarrierId'], 'shippingintegrationservice_name_carrierid');
            $table->unique(['shippingIntegrationCarrierId', 'serviceId'], 'shippingintegrationservice_id_serviceid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ShippingIntegrationService');
    }
}
