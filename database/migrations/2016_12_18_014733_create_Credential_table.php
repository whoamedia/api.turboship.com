<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCredentialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Credential', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('value', 500);

            $table->integer('integratedServiceId')->unsigned()->index();
            $table->foreign('integratedServiceId')->references('id')->on('IntegratedService');

            $table->integer('integrationCredentialId')->unsigned()->index();
            $table->foreign('integrationCredentialId')->references('id')->on('IntegrationCredential');


            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();

            $table->unique(['integratedServiceId', 'integrationCredentialId'], 'clientcredential_unique_keys');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Credential');
    }

}
