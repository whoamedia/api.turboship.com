<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cart', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('barCode', 150)->index();

            $table->integer('organizationId')->unsigned()->index();
            $table->foreign('organizationId')->references('id')->on('Organization');

            $table->unique(['barCode', 'organizationId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Cart');
    }
}
