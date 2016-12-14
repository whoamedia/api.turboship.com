<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContinentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Continent', function (Blueprint $table) 
        {
            $table->increments('id')->unsigned();
            $table->string('name', 30)->unique();
            $table->string('symbol', 3)->unique();
            
        });
        DB::statement("ALTER TABLE Continent COMMENT = 'Based on ISO 3166   http://www.iso.org/iso/country_codes'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Continent');
    }
}
