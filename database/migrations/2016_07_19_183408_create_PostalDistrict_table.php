<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostalDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PostalDistrict', function (Blueprint $table) 
        {
            $table->increments('id')->unsigned();
            $table->integer('countryId')->unsigned()->index();
            $table->string('name', 100)->unique();
            $table->string('french', 100)->unique();
            $table->string('symbol', 100)->unique();
        });

        //  Add the foreign keys
        Schema::table('PostalDistrict', function (Blueprint $table)
        {
            $table->foreign('countryId')->references('id')->on('Country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('PostalDistrict');
    }
}
