<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingApiServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ShippingApiService', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 100);

            $table->integer('shippingApiCarrierId')->unsigned()->index('ShippingApiService_carrierId_index');
            $table->foreign('shippingApiCarrierId', 'shippingApiCarrierId_index')->references('id')->on('ShippingApiCarrier');

            $table->integer('serviceId')->unsigned()->index();
            $table->foreign('serviceId')->references('id')->on('Service');


            $table->unique(['name', 'shippingApiCarrierId'], 'shippingapiintegrationservice_name_carrierid');
            $table->unique(['shippingApiCarrierId', 'serviceId'], 'shippingapiintegrationservice_id_serviceid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ShippingApiService');
    }
}
