<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTotePickTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TotePick', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('toteId')->unsigned()->index();
            $table->foreign('toteId')->references('id')->on('Tote');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('TotePick');
    }

}
