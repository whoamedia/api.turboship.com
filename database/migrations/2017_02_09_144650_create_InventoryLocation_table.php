<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('InventoryLocation', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('barCode', 150)->index();
            $table->integer('inventoryLocationTypeId')->unsigned()->index();

            $table->integer('organizationId')->unsigned()->index();
            $table->foreign('organizationId')->references('id')->on('Organization');

            $table->unique(['barCode', 'inventoryLocationTypeId', 'organizationId'], 'inventory_location_unique_keys');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('InventoryLocation');
    }
}
