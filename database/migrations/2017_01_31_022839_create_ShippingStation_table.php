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

            $table->string('name');

            $table->integer('organizationId')->unsigned()->index();
            $table->foreign('organizationId')->references('id')->on('Organization');

            $table->integer('printerId')->unsigned()->index()->nullable()->default(null);
            $table->foreign('printerId')->references('id')->on('Printer');
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
