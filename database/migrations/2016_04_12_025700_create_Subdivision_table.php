<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubdivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Subdivision', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 100)->index();
            $table->string('symbol', 50)->unique();
            $table->string('localSymbol', 50)->index();
            $table->integer('countryId')->unsigned()->index();
            $table->integer('subdivisionTypeId')->unsigned()->index();
        });

        DB::statement("ALTER TABLE Subdivision COMMENT = 'Based on ISO 3166-2   http://www.iso.org/iso/catalogue_detail?csnumber=63546'");

        DB::statement("ALTER TABLE Subdivision ADD CONSTRAINT unique_countryId_localSymbol
                          UNIQUE (countryId, localSymbol)");

        //  Add the foreign keys
        Schema::table('Subdivision', function (Blueprint $table)
        {
            $table->foreign('countryId')->references('id')->on('Country');
            $table->foreign('subdivisionTypeId')->references('id')->on('SubdivisionType');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Subdivision');
    }
}
