<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('VariantInventory', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('variantId')->unsigned()->index();
            $table->foreign('variantId')->references('id')->on('Variant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('VariantInventory');
    }
}
