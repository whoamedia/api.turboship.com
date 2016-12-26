<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingApiIntegrationServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ShippingApiIntegrationService', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 100);

            $table->integer('shippingApiIntegrationCarrierId')->unsigned()->index('ShippingApiIntegrationService_carrierId_index');
            $table->foreign('shippingApiIntegrationCarrierId', 'shippingApiIntegrationCarrierId_index')->references('id')->on('ShippingApiIntegrationCarrier');

            $table->integer('serviceId')->unsigned()->index();
            $table->foreign('serviceId')->references('id')->on('Service');


            $table->unique(['name', 'shippingApiIntegrationCarrierId'], 'shippingapiintegrationservice_name_carrierid');
            $table->unique(['shippingApiIntegrationCarrierId', 'serviceId'], 'shippingapiintegrationservice_id_serviceid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ShippingApiIntegrationService');
    }
}
