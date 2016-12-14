<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Product', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name')->index();
            $table->text('description')->nullable()->default(NULL);

            $table->integer('clientId')->unsigned()->index();


            //  Boilerplate
            $table->integer('statusId')->unsigned()->index()->default(1);
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });

        Schema::table('Product', function (Blueprint $table)
        {
            $table->foreign('clientId')->references('id')->on('Client');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Product');
    }

}
