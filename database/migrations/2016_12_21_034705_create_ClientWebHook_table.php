<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientWebHookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ClientWebHook', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->string('externalId')->index()->nullable()->default(null);

            $table->integer('clientIntegrationId')->unsigned()->index();
            $table->foreign('clientIntegrationId')->references('id')->on('ClientIntegration');

            $table->integer('integrationWebHookId')->unsigned()->index();
            $table->foreign('integrationWebHookId')->references('id')->on('IntegrationWebHook');

            $table->unique(['clientIntegrationId', 'integrationWebHookId']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ClientWebHook');
    }
}
