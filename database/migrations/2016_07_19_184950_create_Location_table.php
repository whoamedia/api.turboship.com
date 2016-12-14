<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Location', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('street1')->index();
            $table->string('street2')->index()->nullable();
            $table->string('city')->index();
            $table->string('postalCode', 50)->index();


            $table->integer('subdivisionId')->unsigned()->index();
            $table->foreign('subdivisionId')->references('id')->on('Subdivision');


            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Location');
    }
}
