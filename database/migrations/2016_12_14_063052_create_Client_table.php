<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Client', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 100);

            $table->integer('organizationId')->unsigned()->index();
            $table->foreign('organizationId')->references('id')->on('Organization');


            //  Boilerplate
            $table->integer('statusId')->unsigned()->index()->default(1);
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Client');
    }

}
