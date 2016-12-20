<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostageIdFKToShipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Shipment', function (Blueprint $table)
        {
            $table->foreign('postageId')->references('id')->on('Postage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Shipment', function (Blueprint $table)
        {
            $table->dropForeign('shipment_postageid_foreign');
        });
    }
}
