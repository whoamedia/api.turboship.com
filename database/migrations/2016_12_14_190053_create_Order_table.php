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
        Schema::create('Order', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('toAddressId')->unsigned()->index()->nullable()->default(NULL);
            $table->foreign('toAddressId')->references('id')->on('Address');

            $table->integer('providedAddressId')->unsigned()->index();
            $table->foreign('providedAddressId')->references('id')->on('ProvidedAddress');

            $table->integer('billingAddressId')->unsigned()->index();
            $table->foreign('billingAddressId')->references('id')->on('ProvidedAddress');

            $table->integer('orderSourceId')->unsigned()->index();
            $table->foreign('orderSourceId')->references('id')->on('OrderSource');

            $table->integer('clientId')->unsigned()->index();
            $table->foreign('clientId')->references('id')->on('Client');


            $table->string('externalId')->index();
            $table->datetime('externalCreatedAt')->index();
            $table->decimal('basePrice', 10, 2)->unsigned()->index();
            $table->decimal('totalDiscount', 10, 2)->unsigned()->index();
            $table->decimal('totalTaxes', 10, 2)->unsigned()->index();
            $table->decimal('totalItemsPrice', 10, 2)->unsigned()->index();
            $table->decimal('totalPrice', 10, 2)->unsigned()->index();


            //  Boilerplate
            $table->integer('statusId')->unsigned()->index();
            $table->foreign('statusId')->references('id')->on('OrderStatus');
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();

            $table->unique(['externalId', 'orderSourceId', 'clientId']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Order');
    }

}
