<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PickItem', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('pickLocationId')->unsigned()->index();
            $table->foreign('pickLocationId')->references('id')->on('PickLocation');

            $table->integer('variantId')->unsigned()->index();
            $table->foreign('variantId')->references('id')->on('Variant');

            $table->integer('quantity')->unsigned()->index();

            $table->integer('toteId')->unsigned()->index();
            $table->foreign('toteId')->references('id')->on('Tote');

            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->datetime('completedAt')->nullable()->default(NULL)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('PickItem');
    }
}
