<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVariantIdFKToOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('OrderItem', function (Blueprint $table)
        {
            $table->foreign('variantId')->references('id')->on('Variant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('OrderItem', function (Blueprint $table)
        {
            $table->dropForeign('orderitem_variantid_foreign');
        });
    }
}
