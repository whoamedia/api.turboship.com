<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickToteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PickTote', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('pickInstructionId')->unsigned()->index();
            $table->foreign('pickInstructionId')->references('id')->on('PickInstruction');

            $table->integer('toteId')->unsigned()->index()->nullable()->default(null);
            $table->foreign('toteId')->references('id')->on('Tote');

            $table->integer('shipmentId')->unsigned()->index()->nullable()->default(null);
            $table->foreign('shipmentId')->references('id')->on('Shipment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('PickTote');
    }
}
