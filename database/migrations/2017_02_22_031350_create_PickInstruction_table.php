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

            $table->boolean('isAssigned')->index()->default(false);

            $table->integer('organizationId')->unsigned()->index();
            $table->foreign('organizationId')->references('id')->on('Organization');

            $table->integer('staffId')->unsigned()->index();
            $table->foreign('staffId')->references('id')->on('Staff');

            $table->integer('createdById')->unsigned()->index();
            $table->foreign('createdById')->references('id')->on('Staff');

            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->datetime('startedAt')->nullable()->default(NULL)->index();
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
