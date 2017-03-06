<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickInstructionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PickInstruction', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('pickInstructionTypeId')->unsigned()->index();

            $table->integer('staffId')->unsigned()->index();
            $table->foreign('staffId')->references('id')->on('Staff');

            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->datetime('completedAt')->nullable()->default(NULL)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('PickInstruction');
    }
}
