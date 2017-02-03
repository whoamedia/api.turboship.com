<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ShippingStation', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('organizationId')->unsigned()->index();
            $table->foreign('organizationId')->references('id')->on('Organization');

            $table->integer('shippingStationTypeId')->unsigned()->index();
            $table->foreign('shippingStationTypeId')->references('id')->on('ShippingStationType');

            $table->integer('printerId')->unsigned()->index();
            $table->foreign('printerId')->references('id')->on('Printer');


            $table->integer('shippingApiServiceId')->unsigned()->index();
            $table->foreign('shippingApiServiceId')->references('id')->on('ShippingApiService');

            $table->string('externalShipmentId', 100)->index()->nullable()->default(null);
            $table->string('externalId', 100)->index()->nullable()->default(null);

            $table->decimal('rate', 10, 2);
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
        Schema::drop('ShippingStation');
    }
}
