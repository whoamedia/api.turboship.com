<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingIntegrationCarrierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ShippingIntegrationCarrier', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 100)->unique();

            $table->integer('shippingIntegrationId')->unsigned()->index();
            $table->foreign('shippingIntegrationId')->references('id')->on('ShippingIntegration');

            $table->integer('carrierId')->unsigned()->index();
            $table->foreign('carrierId')->references('id')->on('Carrier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ShippingIntegrationCarrier');
    }
}
