<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Orders', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('providedAddressId')->unsigned()->index();
            $table->foreign('providedAddressId')->references('id')->on('Address');

            $table->integer('shippingAddressId')->unsigned()->index();
            $table->foreign('shippingAddressId')->references('id')->on('Address');

            $table->integer('billingAddressId')->unsigned()->index()->nullable()->default(null);
            $table->foreign('billingAddressId')->references('id')->on('Address');

            $table->integer('sourceId')->unsigned()->index();
            $table->foreign('sourceId')->references('id')->on('Source');

            $table->integer('clientId')->unsigned()->index();
            $table->foreign('clientId')->references('id')->on('Client');


            $table->string('externalId')->index();
            $table->string('name')->index()->nullable()->default(null);
            $table->datetime('externalCreatedAt')->index();
            $table->decimal('externalWeight', 10, 2)->unsigned()->index()->nullable()->default(null);
            $table->decimal('basePrice', 10, 2)->unsigned()->index();
            $table->decimal('totalDiscount', 10, 2)->unsigned()->index();
            $table->decimal('totalTaxes', 10, 2)->unsigned()->index();
            $table->decimal('totalItemsPrice', 10, 2)->unsigned()->index();
            $table->decimal('totalPrice', 10, 2)->unsigned()->index();


            //  Boilerplate
            $table->integer('statusId')->unsigned()->index();
            $table->foreign('statusId')->references('id')->on('OrderStatus');

            $table->integer('shipmentStatusId')->unsigned()->index()->nullable()->default(NULL);
            $table->foreign('shipmentStatusId')->references('id')->on('ShipmentStatus');

            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();

            $table->unique(['externalId', 'sourceId', 'clientId']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Orders');
    }

}
