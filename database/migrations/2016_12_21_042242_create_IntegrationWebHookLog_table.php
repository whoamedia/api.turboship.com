<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegrationWebHookLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('IntegrationWebHookLog', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('clientIntegrationId')->unsigned()->index();
            $table->foreign('clientIntegrationId')->references('id')->on('ClientIntegration');

            $table->integer('integrationWebHookId')->unsigned()->index();
            $table->foreign('integrationWebHookId')->references('id')->on('IntegrationWebHook');

            $table->text('incomingMessage');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('IntegrationWebHookLog');
    }
}
