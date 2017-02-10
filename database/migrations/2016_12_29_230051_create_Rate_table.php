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

            $table->decimal('weight', 10, 2);

            $table->decimal('total', 10, 2)->unsigned();
            $table->decimal('base', 10, 2)->unsigned();
            $table->decimal('tax', 10, 2)->unsigned()->default(0.00);
            $table->decimal('fuelSurcharge', 10, 2)->unsigned()->default(0.00);


            $table->decimal('retailRate', 10, 2)->nullable()->default(NULL);
            $table->decimal('listRate', 10, 2)->nullable()->default(NULL);
            $table->integer('deliveryDays')->unsigned()->nullable()->default(NULL);
            $table->datetime('deliveryDate')->nullable()->default(NULL);
            $table->boolean('deliveryDateGuaranteed')->nullable()->default(NULL);


            $table->boolean('purchased')->index()->default(TRUE);

            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->datetime('deletedAt')->nullable()->default(NULL)->index();
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
