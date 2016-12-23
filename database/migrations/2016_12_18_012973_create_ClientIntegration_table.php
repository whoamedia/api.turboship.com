<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientIntegrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ClientIntegration', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('clientIntegrationTypId')->unsigned()->index();



            $table->integer('integrationId')->unsigned()->index();
            $table->foreign('integrationId')->references('id')->on('Integration');

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
        Schema::drop('ClientIntegration');
    }
}
