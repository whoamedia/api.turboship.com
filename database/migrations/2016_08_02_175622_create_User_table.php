<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('User', function (Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('firstName', 100)->index();
            $table->string('lastName', 100)->index();
            $table->string('email', 100)->unique();
            $table->string('password', 500);
            $table->integer('organizationId')->unsigned()->index();

            $table->integer('imageId')->unsigned()->index()->nullable()->default(NULL);

            //  Boilerplate
            $table->integer('statusId')->unsigned()->index()->default(1);
            $table->datetime('createdAt')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
        });

        Schema::table('User', function (Blueprint $table)
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
        Schema::drop('User');
    }
}
