<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ProductAlias', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('clientId')->unsigned()->index();
            $table->foreign('clientId')->references('id')->on('Client');

            $table->integer('productId')->unsigned()->index();
            $table->foreign('productId')->references('id')->on('Product');

            $table->integer('sourceId')->unsigned()->index();
            $table->foreign('sourceId')->references('id')->on('Source');

            $table->string('externalId')->index();
            $table->datetime('externalCreatedAt')->index();
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();

            $table->unique(['clientId', 'externalId', 'sourceId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ProductAlias');
    }

}
