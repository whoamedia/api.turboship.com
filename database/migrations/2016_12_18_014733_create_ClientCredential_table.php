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

            $table->integer('integrationCredentialId')->unsigned()->index();
            $table->foreign('integrationCredentialId')->references('id')->on('IntegrationCredential');

            $table->integer('clientId')->unsigned()->index();
            $table->foreign('clientId')->references('id')->on('Client');



            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();

            $table->unique(['integrationCredentialId', 'clientId']);
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
