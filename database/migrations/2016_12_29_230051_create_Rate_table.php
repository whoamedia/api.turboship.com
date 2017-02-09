<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Rate', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('shipmentId')->unsigned()->index();
            $table->foreign('shipmentId')->references('id')->on('Shipment');

            $table->integer('integratedShippingApiId')->unsigned()->index();
            $table->foreign('integratedShippingApiId')->references('id')->on('IntegratedShippingApi');


            $table->integer('shippingApiServiceId')->unsigned()->index();
            $table->foreign('shippingApiServiceId')->references('id')->on('ShippingApiService');

            $table->string('externalShipmentId', 100)->index()->nullable()->default(null);
            $table->string('externalId', 100)->index()->nullable()->default(null);

            $table->decimal('rate', 10, 2);


            $table->decimal('retailRate', 10, 2);
            $table->decimal('listRate', 10, 2);
            $table->integer('deliveryDays')->unsigned();
            $table->datetime('deliveryDate');
            $table->boolean('deliveryDateGuaranteed');



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
        Schema::drop('Rate');
    }
}
