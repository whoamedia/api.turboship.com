<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Service', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('carrierId')->unsigned()->index();
            $table->foreign('carrierId')->references('id')->on('Carrier');

            $table->string('name', 50)->index();
            $table->boolean('isDomestic')->index();


            $table->unique(['carrierId', 'name']);
        });
    }

    public function down()
    {
        Schema::drop('Service');
    }
}
