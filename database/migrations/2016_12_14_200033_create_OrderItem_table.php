<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('OrderItem', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('sku')->index();

            $table->integer('orderId')->unsigned()->index();
            $table->foreign('orderId')->references('id')->on('Order');





            $table->integer('quantityPurchased')->unsigned()->index();
            $table->integer('quantityToFulfill')->unsigned()->index();
            $table->decimal('basePrice', 10, 2)->unsigned()->index();
            $table->decimal('totalDiscount', 10, 2)->unsigned()->index();
            $table->decimal('totalTaxes', 10, 2)->unsigned()->index();


            $table->string('externalId')->index();
            $table->string('externalProductId')->index()->nullable()->default(NULL);
            $table->string('externalVariantId')->index()->nullable()->default(NULL);


            $table->integer('statusId')->unsigned()->index();
            $table->foreign('statusId')->references('id')->on('OrderStatus');

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
        Schema::drop('OrderItem');
    }
}
