<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Image', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('path');

            $table->integer('crmSourceId')->unsigned()->index();
            $table->foreign('crmSourceId')->references('id')->on('CRMSource');

            $table->string('externalId')->index()->nullable()->default(null);

            $table->datetime('externalCreatedAt')->index();
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
        Schema::drop('Image');
    }
}
