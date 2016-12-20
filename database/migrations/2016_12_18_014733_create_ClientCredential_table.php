<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientCredentialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ClientCredential', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('value', 500);

            $table->integer('clientIntegrationId')->unsigned()->index();
            $table->foreign('clientIntegrationId')->references('id')->on('ClientIntegration');

            $table->integer('integrationCredentialId')->unsigned()->index();
            $table->foreign('integrationCredentialId')->references('id')->on('IntegrationCredential');


            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();

            $table->unique(['clientIntegrationId', 'integrationCredentialId'], 'clientcredential_unique_keys');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ClientCredential');
    }

}
