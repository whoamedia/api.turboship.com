<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Inventory', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('inventoryTypeId')->unsigned()->index();

            $table->integer('inventoryLocationId')->unsigned()->index();
            $table->foreign('inventoryLocationId')->references('id')->on('InventoryLocation');

            $table->integer('organizationId')->unsigned()->index();
            $table->foreign('organizationId')->references('id')->on('Organization');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Inventory');
    }
}
