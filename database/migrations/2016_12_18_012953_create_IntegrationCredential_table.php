<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegrationCredentialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('IntegrationCredential', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 100)->index();
            $table->boolean('isRequired')->index();

            $table->integer('integrationId')->index()->unsigned();
            $table->foreign('integrationId')->references('id')->on('Integration');

            $table->unique(['integrationId', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('IntegrationCredential');
    }
}
