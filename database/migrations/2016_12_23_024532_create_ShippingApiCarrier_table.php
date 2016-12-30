<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingApiCarrierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ShippingApiCarrier', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 100)->unique();

            $table->integer('shippingApiIntegrationId')->unsigned()->index();
            $table->foreign('shippingApiIntegrationId')->references('id')->on('ShippingApiIntegration');

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
        Schema::drop('ShippingApiCarrier');
    }
}
