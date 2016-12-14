<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidedAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ProvidedAddress', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('firstName')->nullable()->default(NULL);
            $table->string('lastName')->nullable()->default(NULL);
            $table->string('company')->nullable()->default(NULL);
            $table->string('street1')->nullable()->default(NULL);
            $table->string('street2')->nullable()->default(NULL);
            $table->string('city')->nullable()->default(NULL);
            $table->string('postalCode')->nullable()->default(NULL);
            $table->integer('subdivision')->nullable()->default(NULL);
            $table->integer('country')->nullable()->default(NULL);
            $table->string('phone')->nullable()->default(NULL);
            $table->string('email')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ProvidedAddress');
    }
}
