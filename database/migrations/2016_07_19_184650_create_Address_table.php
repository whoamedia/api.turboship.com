<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Address', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('firstName', 100)->index();
            $table->string('lastName', 100)->index();
            $table->string('company', 100)->nullable();
            $table->string('street1', 100)->index();
            $table->string('street2', 100)->index()->nullable();
            $table->string('city', 100)->index()->nullable();
            $table->string('postalCode', 20)->index()->nullable();


            $table->string('stateProvince', 100)->index()->nullable();
            $table->integer('subdivisionId')->unsigned()->index()->nullable()->default(NULL);

            $table->string('countryCode', 100)->index()->nullable();
            $table->integer('countryId')->unsigned()->index()->nullable()->default(NULL);




            $table->string('phone', 20)->index()->nullable();
            $table->string('email', 50)->index()->nullable();


            // Boilerplate
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });

        //  Add the foreign keys
        Schema::table('Address', function (Blueprint $table)
        {
            $table->foreign('subdivisionId')->references('id')->on('Subdivision');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Address');
    }
}
