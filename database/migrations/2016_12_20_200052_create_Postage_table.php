<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Postage', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->string('trackingNumber', 100)->index();
            $table->string('labelPath');
            $table->string('zplPath')->nullable()->default(null);
            $table->integer('shipmentId')->unsigned()->index();
            $table->foreign('shipmentId')->references('id')->on('Shipment');
            $table->integer('rateId')->unsigned()->index();
            $table->string('externalId', 100)->index();
            $table->datetime('voidedAt')->nullable()->default(NULL)->index();
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });
    }

    public function down()
    {
        Schema::drop('Postage');
    }
}
