<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Shipper', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->string('name', 100)->index();

            $table->integer('organizationId')->unsigned()->index();
            $table->foreign('organizationId')->references('id')->on('Organization');

            $table->integer('addressId')->unsigned()->index();
            $table->foreign('addressId')->references('id')->on('Address');

            $table->integer('returnAddressId')->unsigned()->index();
            $table->foreign('returnAddressId')->references('id')->on('Address');


            $table->unique(['organizationId', 'name']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Shipper');
    }
}
