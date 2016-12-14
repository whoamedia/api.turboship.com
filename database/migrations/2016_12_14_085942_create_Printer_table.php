<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrinterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Printer', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name')->index();

            $table->integer('organizationId')->unsigned()->index();


            //  Boilerplate
            $table->integer('statusId')->unsigned()->index()->default(1);
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });

        Schema::table('Printer', function (Blueprint $table)
        {
            $table->foreign('organizationId')->references('id')->on('Organization');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Printer');
    }
}
