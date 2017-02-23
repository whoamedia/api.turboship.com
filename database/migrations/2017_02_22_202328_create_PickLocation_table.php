<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PickLocation', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();

            $table->integer('pickInstructionId')->unsigned()->index();
            $table->foreign('pickInstructionId')->references('id')->on('PickInstruction');

            $table->integer('binId')->unsigned()->index();
            $table->foreign('binId')->references('id')->on('Bin');

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
        Schema::drop('PickLocation');
    }
}
