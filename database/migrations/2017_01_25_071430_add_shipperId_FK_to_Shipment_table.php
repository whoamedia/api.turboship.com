<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShipperIdFKToShipmentTable extends Migration
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
            $table->foreign('shipperId')->references('id')->on('Shipper');
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
            $table->dropForeign('shipment_shipperid_foreign');
        });
    }
}
