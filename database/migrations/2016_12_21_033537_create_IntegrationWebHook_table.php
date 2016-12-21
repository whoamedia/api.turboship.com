<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegrationWebHookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('IntegrationWebHook', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('integrationId')->unsigned()->index();
            $table->foreign('integrationId')->references('id')->on('Integration');
            $table->boolean('isActive')->index();
            $table->string('topic', 100)->index();

            $table->unique(['integrationId', 'topic']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('IntegrationWebHook');
    }
}
