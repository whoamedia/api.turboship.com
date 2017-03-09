<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('UserPermission', function (Blueprint $table)
        {
            $table->integer('userId')->unsigned()->index();
            $table->foreign('userId')->references('id')->on('User');


            $table->integer('permissionId')->unsigned()->index();
            $table->foreign('permissionId')->references('id')->on('Permission');

            $table->primary(['userId', 'permissionId']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('UserPermission');
    }
}
