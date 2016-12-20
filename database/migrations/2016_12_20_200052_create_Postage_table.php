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

            $table->string('trackingNumber')->index();
            $table->string('labelPath');

            $table->integer('shipmentId')->unsigned()->index();
            $table->foreign('shipmentId')->references('id')->on('Shipment');

            $table->integer('serviceId')->unsigned()->index();
            $table->foreign('serviceId')->references('id')->on('Service');

            $table->decimal('weight', 10, 2);

            $table->decimal('totalPrice', 10, 2)->unsigned();
            $table->decimal('basePrice', 10, 2)->unsigned();
            $table->decimal('totalTaxes', 10, 2)->unsigned()->default(0.00);
            $table->decimal('fuelSurcharge', 10, 2)->unsigned()->default(0.00);

            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });

    }

    public function down()
    {
        Schema::drop('Postage');
    }
}
