<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UserRole', function (Blueprint $table)
        {
            $table->integer('userId')->unsigned()->index();
            $table->foreign('userId')->references('id')->on('User');


            $table->integer('roleId')->unsigned()->index();
            $table->foreign('roleId')->references('id')->on('Role');

            $table->primary(['userId', 'roleId']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('UserRole');
    }
}
