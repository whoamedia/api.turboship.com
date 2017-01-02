<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Variant', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('productId')->unsigned()->index();
            $table->foreign('productId')->references('id')->on('Product');

            $table->integer('sourceId')->unsigned()->index();
            $table->foreign('sourceId')->references('id')->on('Source');

            $table->integer('clientId')->unsigned()->index();
            $table->foreign('clientId')->references('id')->on('Client');

            $table->integer('countryOfOriginId')->unsigned()->index();
            $table->foreign('countryOfOriginId')->references('id')->on('Country');

            $table->string('title');
            $table->string('barcode')->index();
            $table->string('originalSku')->nullable()->default(null);
            $table->string('sku')->index();
            $table->decimal('price', 10, 2)->unsigned();
            $table->decimal('weight', 10, 2)->unsigned();
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->string('externalId')->index();
            $table->datetime('externalCreatedAt')->index();
            $table->unique(['clientId', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Variant');
    }

}
