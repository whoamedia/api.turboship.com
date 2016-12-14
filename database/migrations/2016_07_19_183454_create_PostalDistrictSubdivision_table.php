<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostalDistrictSubdivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PostalDistrictSubdivision', function (Blueprint $table) 
        {
            $table->increments('id')->unsigned();
            $table->integer('postalDistrictId')->unsigned()->index();
            $table->integer('subdivisionId')->unsigned()->index();
        });

        //  Add the foreign keys
        Schema::table('PostalDistrictSubdivision', function (Blueprint $table)
        {
            $table->foreign('postalDistrictId')->references('id')->on('PostalDistrict');
            $table->foreign('subdivisionId')->references('id')->on('Subdivision');
        });

        DB::statement("ALTER TABLE PostalDistrictSubdivision 
                          ADD CONSTRAINT unique_postalDistrictId_subdivisionId
                          UNIQUE (postalDistrictId, subdivisionId)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('PostalDistrictSubdivision');
    }
}
