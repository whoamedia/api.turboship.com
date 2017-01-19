<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegratedWebHookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('IntegratedWebHook', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->string('externalId')->index()->nullable()->default(null);
            $table->string('url')->nullable()->default(null);

            $table->integer('integratedServiceId')->unsigned()->index();
            $table->foreign('integratedServiceId')->references('id')->on('IntegratedService');

            $table->integer('integrationWebHookId')->unsigned()->index();
            $table->foreign('integrationWebHookId')->references('id')->on('IntegrationWebHook');

            $table->unique(['integratedServiceId', 'integrationWebHookId'], 'IntegratedWebHook_unique_keys');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('IntegratedWebHook');
    }
}
