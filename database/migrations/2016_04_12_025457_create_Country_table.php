<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Country', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 30)->unique();
            $table->string('iso2', 2)->unique();
            $table->string('iso3', 3)->unique();
            $table->string('isoNumeric', 3)->unique();
            $table->string('fipsCode', 2)->index();
            $table->string('capital', 100)->index()->nullable();
            $table->boolean('isEU')->default(0)->index();
            $table->boolean('isUK')->default(0)->index();
            $table->boolean('isUSTerritory')->default(0)->index();
            
            $table->integer('continentId')->unsigned()->index();
        });

        Schema::table('Country', function (Blueprint $table)
        {
            $table->foreign('continentId')->references('id')->on('Continent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Country');
    }
}
