<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Bin', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('aisle', 150)->index();
            $table->string('section', 150)->index();
            $table->string('row', 150)->index();
            $table->string('col', 150)->index();

            $table->unique(['aisle', 'section', 'row', 'col'], 'bin_unique_constraints');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Bin');
    }
}
