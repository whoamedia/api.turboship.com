<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFKsToPostageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Postage', function (Blueprint $table)
        {
            $table->foreign('rateId')->references('id')->on('Rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Postage', function (Blueprint $table)
        {
            $table->dropForeign('postage_rateid_foreign');
        });
    }
}
