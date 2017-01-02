<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Shipment', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('fromAddressId')->unsigned()->index();
            $table->foreign('fromAddressId')->references('id')->on('Address');

            $table->integer('toAddressId')->unsigned()->index();
            $table->foreign('toAddressId')->references('id')->on('Address');

            $table->integer('returnAddressId')->unsigned()->index();
            $table->foreign('returnAddressId')->references('id')->on('Address');

            $table->integer('serviceId')->unsigned()->index()->nullable()->default(NULL);
            $table->foreign('serviceId')->references('id')->on('Service');

            $table->integer('postageId')->unsigned()->index()->nullable()->default(NULL);

            $table->integer('shippingContainerId')->unsigned()->index()->nullable()->default(NULL);

            $table->decimal('weight', 10, 2)->nullable()->default(NULL);

            $table->integer('statusId')->unsigned()->index()->nullable()->default(NULL);
            $table->foreign('statusId')->references('id')->on('ShipmentStatus');

            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });

    }

    public function down()
    {
        Schema::drop('Shipment');
    }

}
