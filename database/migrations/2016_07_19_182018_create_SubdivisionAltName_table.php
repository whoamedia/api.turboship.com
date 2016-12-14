<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubdivisionAltNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SubdivisionAltName', function (Blueprint $table) 
        {
            $table->increments('id')->unsigned();
            $table->integer('subdivisionId')->unsigned()->index();
            $table->string('name', 100)->index();


            // Boilerplate
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });

        //  Add the foreign keys
        Schema::table('SubdivisionAltName', function (Blueprint $table)
        {
            $table->foreign('subdivisionId')->references('id')->on('Subdivision');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('SubdivisionAltName');
    }
}
