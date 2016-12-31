<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ClientOption', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('clientId')->unsigned()->index();
            $table->foreign('clientId')->references('id')->on('Client');

            $table->integer('defaultShipperId')->unsigned()->index()->nullable()->default(null);
            $table->foreign('defaultShipperId')->references('id')->on('Shipper');

            $table->integer('defaultIntegratedShippingApiId')->unsigned()->index()->nullable()->default(null);
            $table->foreign('defaultIntegratedShippingApiId')->references('id')->on('IntegratedShippingApi');


            $table->string('defaultShipToPhone', 20)->nullable()->default(null);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ClientOption');
    }
}
