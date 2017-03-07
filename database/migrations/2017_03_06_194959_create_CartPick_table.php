<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartPickTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CartPick', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('cartId')->unsigned()->index();
            $table->foreign('cartId')->references('id')->on('Cart');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('CartPick');
    }
}
