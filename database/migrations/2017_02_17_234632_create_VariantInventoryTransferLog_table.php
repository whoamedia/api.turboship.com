<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantInventoryTransferLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('VariantInventoryTransferLog', function (Blueprint $table)
        {
            $table->bigIncrements('id')->unsigned();

            $table->integer('variantId')->unsigned()->index();
            $table->foreign('variantId')->references('id')->on('Variant');

            $table->integer('fromInventoryLocationId')->unsigned()->index()->nullable()->default(null);
            $table->foreign('fromInventoryLocationId')->references('id')->on('InventoryLocation');

            $table->integer('toInventoryLocationId')->unsigned()->index();
            $table->foreign('toInventoryLocationId')->references('id')->on('InventoryLocation');

            $table->integer('quantity')->unsigned()->index();

            $table->integer('staffId')->unsigned()->index();
            $table->foreign('staffId')->references('id')->on('Staff');

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
        Schema::drop('VariantInventoryTransferLog');
    }
}
