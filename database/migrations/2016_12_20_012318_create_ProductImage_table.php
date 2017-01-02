<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImageTable extends Migration
{


    public function up()
    {
        Schema::create('ProductImage', function (Blueprint $table)
        {
            $table->integer('productId')->unsigned()->index();
            $table->foreign('productId')->references('id')->on('Product');


            $table->integer('imageId')->unsigned()->index();
            $table->foreign('imageId')->references('id')->on('Image');

            $table->primary(['productId', 'imageId']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ProductImage');
    }
}
