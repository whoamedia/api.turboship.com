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

            $table->integer('serviceId')->unsigned()->index();
            $table->foreign('serviceId')->references('id')->on('Service');

            $table->integer('postageId')->unsigned()->index();

            $table->decimal('weight', 10, 2);

            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });

    }

    public function down()
    {
        Schema::drop('Shipment');
    }

}
