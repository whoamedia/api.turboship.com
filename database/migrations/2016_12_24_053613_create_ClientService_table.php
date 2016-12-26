<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ClientService', function (Blueprint $table)
        {
            $table->integer('clientId')->unsigned()->index();
            $table->foreign('clientId')->references('id')->on('Client');

            $table->integer('serviceId')->unsigned()->index();
            $table->foreign('serviceId')->references('id')->on('Service');

            $table->unique(['clientId', 'serviceId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ClientService');
    }
}
