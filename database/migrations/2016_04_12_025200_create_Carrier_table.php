<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarrierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Carrier', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name', 50)->unique();
            $table->string('symbol', 50)->unique();
        });
    }

    public function down()
    {
        Schema::drop('Carrier');
    }

}
