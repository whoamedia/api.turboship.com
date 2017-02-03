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
            $table->string('description')->nullable()->default(null);
            $table->string('ipAddress');

            $table->integer('printerTypeId')->unsigned()->index();
            $table->integer('organizationId')->unsigned()->index();

        });

        Schema::table('Printer', function (Blueprint $table)
        {
            $table->foreign('printerTypeId')->references('id')->on('PrinterType');
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
