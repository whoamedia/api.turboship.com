<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCartIdFKToToteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Tote', function (Blueprint $table)
        {
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
        Schema::table('Tote', function (Blueprint $table)
        {
            $table->dropForeign('tote_cartid_foreign');
        });
    }
}
