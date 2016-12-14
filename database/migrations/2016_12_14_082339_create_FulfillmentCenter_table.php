<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFulfillmentCenterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('FulfillmentCenter', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name')->index();

            $table->integer('locationId')->unsigned()->index();
            $table->integer('organizationId')->unsigned()->index();


            //  Boilerplate
            $table->integer('statusId')->unsigned()->index()->default(1);
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });

        Schema::table('FulfillmentCenter', function (Blueprint $table)
        {
            $table->foreign('locationId')->references('id')->on('Location');
            $table->foreign('organizationId')->references('id')->on('Organization');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('FulfillmentCenter');
    }

}
